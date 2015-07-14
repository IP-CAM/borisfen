class DOption

  dependencies: []
  option_id: 0

  is_parent: false
  current_value: []
  self_type: false
  element: false
  child: []

  constructor: (@option_id, @self_type, @element, @dependencies) ->

    # add on change event if current option has adjective options
    for option in @dependencies
      if parseInt(option.parent) == parseInt(@option_id) then @is_parent = true
    if @is_parent then @onChange()

  detectChild: ->
    options = $('.options').find 'ul.option'
    for dependency in @dependencies
      if parseInt(dependency.parent) == parseInt(@option_id)
        for option in options
          if parseInt($(option).attr('product-option-id')) == parseInt(dependency.child)
            @child.push option

  onChange: ->
    @detectChild()
    switch @self_type
      when 'radio'    then @changeRadio()
      when 'checkbox' then @changeCheckbox()
      when 'select'   then @changeSelect()
      else console.log 'find unrecognized type of option'

  changeChild: ->
    if @child.length > 0
      for children in @child
          switch $(children).attr 'option-type'
            when 'radio'    then @changeRadioValues children
            when 'checkbox' then @changeCheckboxValues children
            when 'select'   then @changeSelectValues children
            else console.log 'find unrecognized type of option'

  changeRadio : =>
    #bind change event
    $(@element).find('input[type=radio]').change (event) =>
      @current_value = []
      @current_value.push parseInt $(@element).find('input[type=radio]:checked').first().val()
      @changeChild()
    @current_value = []
    @current_value.push parseInt $(@element).find('input[type=radio]:checked').first().val()
    @changeChild()

  changeCheckbox : =>
    #bind change event
    $(@element).find('input[type=checkbox]').change (event) =>
      @current_value = []
      elements = $(@element).find('input[type=checkbox]:checked')
      for element in elements
        @current_value.push parseInt $(element).val()
      @changeChild()
    @current_value = []
    elements = $(@element).find('input[type=checkbox]:checked')
    for element in elements
      @current_value.push parseInt $(element).val()
    @changeChild()

  changeSelect : =>
    #bind change event
    $(@element).change (event) =>
      @current_value = []
      @current_value.push parseInt $(@element).find('option:selected').first().val()
      @changeChild()
    @current_value = []
    @current_value.push parseInt $(@element).find('option:selected').first().val()
    @changeChild()

  changeRadioValues : (element) ->
    child_value = $(element).find 'input[type=radio]'
    for children_value in child_value
      children_value.active = false
    for children_value in child_value
      parent_values = $(children_value).attr 'parent-value-id'
      if parent_values? and parent_values != ''
        parent_values = parent_values.split ' '
        for parent in parent_values
          if parseInt(parent) in @current_value then children_value.active = true
      else
        children_value.active = true
    for children_value in child_value
      if children_value.active
        $(children_value).removeAttr 'disabled'
        $(children_value).removeClass 'disabled'
        $(children_value).parents('label').first().removeClass 'disabled'
      else
        $(children_value).attr 'disabled', 'disabled'
        $(children_value).addClass 'disabled'
        $(children_value).parents('label').first().addClass 'disabled'
      $(children_value).removeAttr 'checked';
      active_nodes = $(element).find '.active'
      for node in active_nodes
        $(node).removeClass 'active'

  changeCheckboxValues : (element) ->
    child_value = $(element).find 'input[type=checkbox]'
    for children_value in child_value
      children_value.active = false
    for children_value in child_value
      parent_values = $(children_value).attr 'parent-value-id'
      if parent_values? and parent_values != ''
        parent_values = parent_values.split ' '
        for parent in parent_values
          if parseInt(parent) in @current_value then children_value.active = true
      else
        children_value.active = true
    for children_value in child_value
      if children_value.active
        $(children_value).removeAttr 'disabled'
        $(children_value).removeClass 'disabled'
      else
        $(children_value).attr 'disabled', 'disabled'
        $(children_value).addClass 'disabled'
      $(children_value).removeAttr('checked');
      $(children_value).checkbox({});

  changeSelectValues : (element) ->
    child_value = $(element).find 'option'
    for children_value in child_value
      children_value.active = false
    for children_value in child_value
      parent_values = $(children_value).attr 'parent-value-id'
      if parent_values? and parent_values != ''
        parent_values = parent_values.split ' '
        for parent in parent_values
          if parseInt(parent) in @current_value then children_value.active = true
      else
        children_value.active = true
    for children_value in child_value
      if children_value.active
        $(children_value).removeAttr 'disabled'
        $(children_value).removeClass 'disabled'
      else
        $(children_value).attr 'disabled', 'disabled'
        $(children_value).addClass 'disabled'
    $(element).find('select').first().selectpicker 'deselectAll'
    $(element).find('select').first().selectpicker 'update'
    $(element).find('select').first().selectpicker 'render'
    $(element).find('select').first().selectpicker 'refresh'

# Init DependedOptions
$(document).ready ->
  dOptions = []
  if chained_options?
    $('ul.option').each ->
      dOptions.push(
        new DOption(
          $(this).attr('product-option-id'),
          $(this).attr('option-type'),
          @,
          chained_options
        )
      )