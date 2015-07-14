$(document).ready ->
  class MCForm
    form: ''
    url: ''
    fields: []
    constructor:(@form) ->
      @url = $(@form).attr 'action'
      @fields.push [
        name: 'textarea[name=enquiry]'
        target: 'text'
        message: LANGS['information_contact']['error_enquiry']
      ]
      @fields.push [
        name: 'input[name=email]'
        target: 'val'
        message: LANGS['information_contact']['error_email']
      ]
      @fields.push [
        name: 'input[name=name]'
        target: 'val'
        message: LANGS['information_contact']['error_name']
      ]
      $(@form).submit (e)=>
        if @validate()
          @submit()
        e.preventDefault()

    validate: ->
      valid = true
      for field in @fields
        field = field[0]
        switch field.target
          when 'val' then field_value = $(@form).find(field.name).val()
          when 'text' then field_value = $(@form).find(field.name).val()
          else field_value = ''
        if(!field_value)
          valid = false
          console.info field.message
          @logError field.message
      valid

    submit: ->
      form_data = $(@form).serializeObject()
      logError  = @logError
      uri       = @url
      $.ajax
        url: uri
        type: 'post'
        dataType: 'json'
        data: form_data
        success: (response) ->
          if response.ok?
            alert response.ok
          else
            console.log response
            if response.errors
              console.log 'ololo'
              for error in response.errors
                console.dir @logError
                console.info error
                logError error
        error: (e1, e2) ->
          console.error(e1)
          console.error(e2)
    logError:(text) ->
      errors_view =
        text: text
        styling: 'bootstrap3'
        addclass: 'float-errors'
        type: 'error'
        icon: 'picon picon-32 picon-fill-color'
        opacity: .8
        nonblock:
          nonblock: true
      new PNotify(errors_view);

  # Attach form-validation
  mainContactForm = new MCForm '#main-contact-form'
