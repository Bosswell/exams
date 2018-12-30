$(function() {
    let checkbox = $('.checkbox-group');
    let squares = $('#questions-panel .square');

    let questions = $('.question-group');
    let questions_quantity = questions.length;

    let latest_question_number = null;
    let quick_view = $('#quick-view');

    let next_question_btn = $('#next-question');

    // Works awesome !
    squares.on('click', function() {
        let square = $(this);
        let question_number = parseInt(square.attr('data-question-number'));

        if (latest_question_number != question_number) {
            questions.eq(latest_question_number).hide();
            questions.eq(question_number).show();

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
})