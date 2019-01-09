$(function() {
  
  let qualifications = $('#qualifications');
  let errors_container = $('#errors-container');
        
  let form = $('#qualification-form');
  let form_qualification_id = form.find('#qualification-id');
  let form_question_quantity = form.find('#question-quantity');

  // Clear input values, in case if user refresh site
  form_qualification_id.val(null);
  form_question_quantity.val(null);

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

      let checkbox_input = checkbox_container.find('select option');
      current_checkbox.attr('data-is-choosen', 'true');
      current_checkbox.addClass('checkbox-active', 300);

      let value = current_checkbox.attr('data-value');

      checkbox_input.filter(function() {
          return this.value == value;
      }).prop("selected", true);

    }
  });

  // TODO poprawić, bo pojawił się checkbox
  form.on('submit', function() {
    let errors = [];
    let isOk = true;

    if (form_qualification_id.val() == '') {
      errors.push('Nie wybrano kwalifikacji.');
      isOk = false;
    }

    if (form_question_quantity.val() == '') {
      errors.push('Nie wybrano ilości pytań.');
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