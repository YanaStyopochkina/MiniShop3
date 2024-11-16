<?php

namespace MiniShop3\Controllers\Customer;

$autoload = dirname(__FILE__, 4) . '/vendor/autoload.php';

require_once($autoload);

use MiniShop3\MiniShop3;
use MiniShop3\Model\msCustomer;
use MiniShop3\Model\msCustomerAddress;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserProfile;
use MODX\Revolution\modUserSetting;
use MODX\Revolution\modX;

use MongoDB\BSON\ObjectId;
use Rakit\Validation\Validator;

class Customer
{
    /** @var modX $modx */
    public $modx;
    /** @var MiniShop3 $ms3 */
    public $ms3;
    /** @var array $config */
    public $config = [];
    protected $token = '';
    protected $validationRules = [];
    protected $validationMessages = [];

    /**
     * Cart constructor.
     *
     * @param MiniShop3 $ms3
     * @param array $config
     */
    public function __construct(MiniShop3 $ms3, array $config = [])
    {
        $this->ms3 = $ms3;
        $this->modx = $ms3->modx;

        $this->config = array_merge([

        ], $config);
        $this->modx->lexicon->load('minishop3:customer');
    }

    public function initialize($token = '')
    {
        if (empty($token)) {
            return false;
        }
        $this->token = $token;

        if (!empty($_SESSION['ms3']['validation']['rules'])) {
            $this->validationRules = $_SESSION['ms3']['validation']['rules'];
        }
        if (!empty($_SESSION['ms3']['validation']['messages'])) {
            $this->validationMessages = $_SESSION['ms3']['validation']['messages'];
        }
        return true;
    }

    public function generateToken()
    {
        $tokenName = $this->modx->getOption('ms3_token_name', null, 'ms3_token');
        $token = md5(rand() . $tokenName);
        $_SESSION['ms3']['customer_token'] = $token;
        $lifetime = $this->modx->getOption('session_gc_maxlifetime', null, '604800') * 1000;
        return $this->success('', compact('token', 'lifetime'));
    }

    public function updateToken($token)
    {
        if (empty($token)) {
            return false;
        }
        $this->token = $token;
        $_SESSION['ms3']['customer_token'] = $token;
        $lifetime = $this->modx->getOption('session_gc_maxlifetime', null, '604800') * 1000;
        return $this->success('', compact('token', 'lifetime'));
    }

    public function registerValidation($rules = [], $messages = [])
    {
        $this->validationRules = [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required|min:10'
        ];

        $this->validationMessages = [
            'required' => 'Обязательно для заполнения',
            'email' => 'Не является email',
            'min' => 'Минимум :min символов',
        ];

        if (!empty($rules)) {
            $this->validationRules = $rules;
        }

        if (!empty($messages)) {
            $this->validationMessages = $messages;
        }

        $_SESSION['ms3']['validation']['rules'] = $this->validationRules;
        $_SESSION['ms3']['validation']['messages'] = $this->validationMessages;
    }

    public function getFields(): array
    {
        if (empty($this->token)) {
            return $this->error('ms3_err_token');
        }
        $msCustomer = $this->modx->getObject(msCustomer::class, [
            'token' => $this->token,
        ]);
        if (!$msCustomer) {
            return $this->success('', $this->modx->getFields(msCustomer::class));
        }
        return $this->success('', $msCustomer->toArray());
    }

    public function getObject(): object|null
    {
        if (empty($this->token)) {
            return null;
        }
        $msCustomer = $this->modx->getObject(msCustomer::class, [
            'token' => $this->token,
        ]);
        if (!$msCustomer) {
            return null;
        }
        return $msCustomer;
    }

    public function getByToken($token)
    {
        if (empty($token)) {
            return null;
        }
        $msCustomer = $this->modx->getObject(msCustomer::class, [
            'token' => $token,
        ]);
        if (!$msCustomer) {
            return null;
        }
        return $msCustomer;
    }

    public function set($data = [])
    {
        if (empty($this->token)) {
            return $this->error('ms3_err_token');
        }
        foreach ($data as $key => $value) {
            $this->add($key, $value);
        }

        return $this->getFields();
    }

    public function add($key, $value)
    {
        if (empty($this->token)) {
            return $this->error('ms3_err_token');
        }

        if (empty($key)) {
            return $this->error('ms3_customer_key_empty');
        }

        //TODO Реализовать событие ПередДобавлениемПоля

        // $response = $$this->ms3->utils->invokeEvent('msOnBeforeAddToOrder', [
        //            'key' => $key,
        //            'value' => $value,
        //            'order' => $this,
        //        ]);
        //        if (!$response['success']) {
        //            return $this->error($response['message']);
        //        }
        //        $value = $response['data']['value'];

        $response = $this->validate($key, $value);
        if (is_array($response)) {
            return $this->error($response[$key]);
        }

        $validated = $response;

        $msCustomer = $this->modx->getObject(msCustomer::class, [
            'token' => $this->token
        ]);
        if ($msCustomer) {
            $msCustomer->set($key, $validated);
        } else {
            $userId = 0;

            // TODO как правильно определить текущего системного пользователя, если тот авторизован?
            if ($this->modx->user->hasSessionContext($this->ms3->config['ctx'])) {
                $userId = $this->modx->user->get('id');
            }
            $msCustomer = $this->modx->newObject(msCustomer::class, [
                'token' => $this->token,
                $key => $validated,
                'user_id' => $userId
            ]);
        }
        $msCustomer->save();

        //TODO Реализовать событие ПослеДобавлениемПоля

        //$response = $$this->ms3->utils->invokeEvent('msOnAddToCustomer', [
        //                    'key' => $key,
        //                    'value' => $validated,
        //                    'customer' => $this,
//                                'mode' => 'new'
        //                ]);
        //                if (!$response['success']) {
        //                    return $this->error($response['message']);
        //                }
        //                $validated = $response['data']['value'];

        return ($validated === false)
            ? $this->error('', [$key => $value])
            : $this->success('', [$key => $validated]);
    }

    public function validate($key, $value)
    {
        $validator = new Validator();

        $validation = $validator->validate(
            [$key => $value],
            [$key => $this->validationRules[$key]],
            $this->validationMessages
        );

        $validation->validate();

        if ($validation->fails()) {
            // handling errors
            $errors = $validation->errors();
            return $errors->firstOfAll();
        } else {
            return $value;
        }

        // TODO валидировать наличие $key в модели msCustomer + разрешение на запись
        //TODO реализовать событие ДоВалидации

        // $eventParams = [
        //            'key' => $key,
        //            'value' => $value,
        //            'customer' => $this,
        //        ];
        //        $response = $this->invokeEvent('msOnBeforeValidateCustomerValue', $eventParams);
        //        $value = $response['data']['value'];

        // TODO валидировать $value

        // TODO реализовать событие ПослеВалидации

        //$eventParams = [
        //            'key' => $key,
        //            'value' => $value,
        //            'customer' => $this,
        //        ];
        //        $response = $this->invokeEvent('msOnValidateCustomerValue', $eventParams);
        //        return $response['data']['value'];

        return $value;
    }

    /**
     * Returns id for current customer. If customer is not exists, registers him and returns id.
     *
     * @return integer $id
     */
    public function getId(): int
    {
        $msCustomer = null;

        // TODO  проработать вопрос с возвращением объекта или массива customer из плагина. Пока не работает
        $response = $this->ms3->utils->invokeEvent('msOnBeforeGetOrderCustomer', [
            'controller' => $this->ms3->order,
            'msCustomer' => $msCustomer,
        ]);
        if (!$response['success']) {
            return $response['message'];
        }

//        if ($customer) {
//            return $customer->get('id');
//        }
//
        $msCustomer = $this->getObject();

        if (empty($msCustomer)) {
            $orderResponse = $this->ms3->order->get();
            $orderData = $orderResponse['data']['order'];
            $customerData = [
                'first_name' => $orderData['address_first_name'],
                'last_name' => $orderData['address_last_name'],
                'phone' => $orderData['address_phone'],
                'email' => $orderData['address_email'],
            ];

            $msCustomer = $this->create($customerData);
        }

        $response = $this->ms3->utils->invokeEvent('msOnGetOrderCustomer', [
            'controller' => $this->ms3->order,
            'msCustomer' => $msCustomer,
        ]);
        if (!$response['success']) {
            return $response['message'];
        }

        if (!empty($msCustomer)) {
            return $msCustomer->get('id');
        }

        return 0;
    }

    public function create(array $customerData): msCustomer|null
    {
        //TODO  event msOnBeforeCreateCustomer
        $msCustomer = $this->modx->newObject(msCustomer::class, $customerData);
        $save = $msCustomer->save();
        if (!$save) {
            return null;
        }
        //TODO  event msOnCreateCustomer
        return $msCustomer;
    }

    public function addAddress(array $customerAddressData): bool
    {
        if (empty($customerAddressData['street'])) {
            return false;
        }

        if (empty($customerAddressData['building'])) {
            return false;
        }

        // TODO вынести в системную настройку список полей для формирования имени и хэша, по примеру ключа опции
        $customerAddressData['name'] = implode(' ', [$customerAddressData['street'], $customerAddressData['building']]);
        $customerAddressData['hash'] = md5($customerAddressData['name']);

        // TODO  сделать события?
        $isExists = $this->modx->getCount(msCustomerAddress::class, [
            'hash' => $customerAddressData['hash'],
        ]);
        if (!empty($isExists)) {
            return false;
        }

        $msCustomerAddress = $this->modx->newObject(msCustomerAddress::class, $customerAddressData);
        $msCustomerAddress->save();

        return true;
    }

    public function getAddresses(int $customer_id = 0): bool|array
    {
        $q = $this->modx->newQuery(msCustomerAddress::class);
        $q->where([
            'customer_id' => $customer_id,
        ]);
        $fields = $this->modx->getSelectColumns(msCustomerAddress::class, 'msCustomerAddress');
        $q->select($fields);
        $q->prepare();
        $q->stmt->execute();
        return $q->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Shorthand for ms3 error method
     *
     * @param string $message
     * @param array $data
     * @param array $placeholders
     *
     * @return array|string
     */
    protected function error($message = '', $data = [], $placeholders = [])
    {
        return $this->ms3->utils->error($message, $data, $placeholders);
    }

    /**
     * Shorthand for ms3 success method
     *
     * @param string $message
     * @param array $data
     * @param array $placeholders
     *
     * @return array|string
     */
    protected function success($message = '', $data = [], $placeholders = [])
    {
        return $this->ms3->utils->success($message, $data, $placeholders);
    }
}
