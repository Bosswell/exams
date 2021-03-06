$(function() {
  
  let errors_container = $('#errors-container');
        
  let form = $('#qualification-form');
  let form_qualification_id = form.find('#qualification-id');
  let form_question_quantity = form.find('#question-quantity');

  let checkbox_group = $('.checkbox-group');

  checkbox_group.on('click', function() {
    let current_checkbox = $(this);
    let checkbox_container = current_checkbox.parent();
    let current_checkbox_group = checkbox_container.find('.checkbox-group');

    if (current_checkbox.attr('data-is-choosen') != 'true') {
      current_checkbox_group.each(function(index) {
        let element = current_checkbox_group.eq(index);
        if (element.attr('data-is-choosen') == 'true') {
          element.removeClass('checkbox-active', 200);
          element.attr('data-is-choosen', 'false');

          return false;
        }
      });

      let checkbox_input = checkbox_container.find('.user-input');
      current_checkbox.attr('data-is-choosen', 'true');
      current_checkbox.addClass('checkbox-active', 300);

      let value = current_checkbox.attr('data-value');

      checkbox_input.val(value);
      checkbox_input.parent().attr('is-filled', 'true');
    }
  });

  // TODO poprawić, bo pojawił się checkbox
  form.on('submit', function() {
    let errors = [];
    let isOk = true;

    if (form_qualification_id.val() === '' || form_question_quantity.val() === '') {
      errors.push('Nie wybrano kwalifikacji lub ilości pytań.');
      isOk = false;
    }

    if (!isOk) {
      let html = '';
      for (var i = 0; i < errors.length; i++) {
        html += '<div class="alert alert-danger">'+errors[i]+'</div>';
      }

      errors_container.html(html);
      return false;
    }
  })
});