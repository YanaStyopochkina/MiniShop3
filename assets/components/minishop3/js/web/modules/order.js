ms3.order = {
  init () {
    const forms = document.querySelectorAll('.ms3_order_form')
    forms.forEach(form => ms3.order.formListener(form))
  },
  formListener (form) {
    const inputs = form.querySelectorAll('input, textarea, select')
    inputs.forEach(input => {
      switch (input.name) {
        case 'address_hash':
          ms3.order.changeAddressListener(input)
          break
        default:
          ms3.order.changeInputListener(input)
      }
    })
  },
  changeAddressListener (input) {
    input.addEventListener('change', async () => {
      const form = input.closest('.ms3_order_form')
      const parent = input.closest('div')
      parent.classList.remove('was-validated')
      input.classList.remove('is-invalid')
      parent.querySelector('.invalid-feedback').textContent = ''
      const formData = new FormData()
      formData.append('key', input.name)
      formData.append('value', input.value)
      const { success, data, message } = await ms3.customer.changeAddress(formData)
      if (success === true) {
        parent.classList.add('was-validated')
        // TODO не менять radio, checkbox, select
        input.value = data[input.name]

        Object.keys(data).forEach((name) => {
          if (form[name] !== undefined) {
            let type
            if (form[name].tagName === 'INPUT') {
              type = form[name].type
            } else if (form[name].tagName === 'TEXTAREA') {
              type = 'textarea'
            } else if (form[name].tagName === 'SELECT') {
              type = 'select'
            }

            switch (type) {
              case 'select':
                form[name].querySelectorAll('option').forEach((option) => {
                  option.selected = option.value === data[name]
                })
                break
              default:
                form[name].value = data[name]
            }
          }
        })
      } else {
        parent.classList.add('was-validated')
        input.classList.add('is-invalid')
        parent.querySelector('.invalid-feedback').textContent = message
      }
    })
  },
  changeInputListener (input) {
    input.addEventListener('change', async () => {
      // const form = input.closest('.ms3_order_form')
      const parent = input.closest('div')
      parent.classList.remove('was-validated')
      input.classList.remove('is-invalid')
      parent.querySelector('.invalid-feedback').textContent = ''
      const formData = new FormData()
      formData.append('key', input.name)
      formData.append('value', input.value)
      const { success, data, message } = await ms3.order.add(formData)
      if (success === true) {
        parent.classList.add('was-validated')
        // TODO не менять radio, checkbox, select
        input.value = data[input.name]
      } else {
        parent.classList.add('was-validated')
        input.classList.add('is-invalid')
        parent.querySelector('.invalid-feedback').textContent = message
      }
    })
  },
  async add (formData) {
    formData.append('ms3_action', 'order/add')
    const response = await ms3.request.send(formData)
    // TODO callback, event
    return response
  },
  async remove (formData) {
    formData.append('ms3_action', 'order/remove')
    await ms3.request.send(formData)
  }
}
