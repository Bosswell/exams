$(function() {
    let checkbox = $('.checkbox-group');
    let squares = $('#questions-panel .square');

    let questions = $('.question-group');
    let questions_quantity = questions.length;
    let form = $('#exam-form');
    let errors_container = $('#errors-container');
    let body = $('body');

    let latest_question_number = null;
    let quick_view = $('#quick-view');

    let next_question_btn = $('#next-question');

    next_question_btn.on('click', function() {

        if (!latest_question_number) {
            latest_question_number = 0;
        }

        let current_question_number = latest_question_number + 1;

        let square = squares.eq(current_question_number);

        if (square.attr('data-is-filled') == 'false') {
            square.removeClass('square-empty');
            square.addClass('square-active', 300);
        }

        questions.eq(latest_question_number).hide();
        questions.eq(current_question_number).show();

        latest_question_number = current_question_number;

        if (questions_quantity <= latest_question_number + 1) {
            next_question_btn.hide();
        } else {
            next_question_btn.show();
        }

        body.scrollTop(0);
    });

    checkbox.on('click', function() {
        let current_checkbox = $(this);
        let answer_group = $(this).parent().parent();
        let answer_input = answer_group.find('.user_answer');
        let value = current_checkbox.attr('data-value');

        let grouped_answers =answer_group.find('.checkbox-group');
        let question_number = answer_group.attr('data-question-number');

        if (current_checkbox.attr('is-active') == 'true') {
            return false;
        }

        grouped_answers.each(function(index){
            grouped_answers.eq(index).removeClass('answer-filled', 250);
            grouped_answers.eq(index).removeClass('answer-active', 250);
            grouped_answers.eq(index).attr('is-active', 'false');
        });

        current_checkbox.addClass('answer-filled', {
            duration: 300,
            easing: "linear"
        });

        let square = squares.eq(question_number);

        square.removeClass('square-empty');
        square.removeClass('square-active');
        square.addClass('answer-filled');
        square.attr('data-is-filled', 'true');

        answer_input.val(value);
        current_checkbox.attr('is-active', 'true');
    });

    // Works awesome !
    squares.on('click', function() {
        let square = $(this);
        let question_number = parseInt(square.attr('data-question-number'));

        if (latest_question_number != question_number) {
            if (square.attr('data-is-filled') == 'false') {
                square.removeClass('square-empty');
                square.addClass('square-active', 300);
            }

            questions.eq(latest_question_number).hide();
            questions.eq(question_number).show();

            if (questions_quantity <= question_number + 1) {
                next_question_btn.hide();
            } else {
                next_question_btn.show();
            }

            latest_question_number = question_number;
        }
    });

    // Works great
    squares.on('mouseenter', function() {
        let square = $(this);

        let question_number = square.attr('data-question-number');
        let questions = $('.question-group .query');
        let question_query = questions.eq(question_number).text();
            
        question_query = jQuery.trim(question_query).substring(0, 80)
                            .split(" ").slice(0, -1).join(" ") + "...";

        quick_view.text(question_query);
    });

    // Nothing to comment
    squares.on('mouseleave', function() { 
        $('#quick-view').text('');
    });

    // It's ok
    form.on('submit', function() {
        let isOk = true;
        squares.each(function(index) {
            let element = squares.eq(index);
            if (element.attr('data-is-filled') != 'true') {
                isOk = false;
                element.addClass('square-error', 300);
            }
        });

        setTimeout(function() {
            squares.removeClass('square-error', 250);
        }, 2000);

        if (!isOk) {
            let html = '<div class="alert alert-danger">Nie udzielono odpowiedzi na wszystkie pytania.</div>';
            errors_container.html(html);

            return false;
        }
     })
})