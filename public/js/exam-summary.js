$(function() {
    let squares = $('#questions-panel .square');
    let questions_container = $('#question-group-container');

    let questions = $('.question-group');

    let latest_question_number = null;
    let quick_view = $('#quick-view');

    /**
     * Show the question that is represented by the corresponding square
     */
    squares.on('click', function() {
        let square = $(this);
        let question_number = parseInt(square.attr('data-question-number'));

        if (latest_question_number != question_number) {
            squares.eq(latest_question_number).removeClass('focus');
            square.addClass('focus',300);
            
            questions.eq(latest_question_number).hide();
            questions.eq(question_number).show();

            latest_question_number = question_number;
        }

        scrollToQuestionsBlock();
    });

    /**
     * Show/hide question query in a quick view block
     */
    squares.on({
        mouseenter: function () {
            let square = $(this);

            let question_number = square.attr('data-question-number');
            let questions = $('.question-group .query');
            let question_query = questions.eq(question_number).text();
                
            question_query = jQuery.trim(question_query).substring(0, 80)
                                .split(" ").slice(0, -1).join(" ") + "...";

            quick_view.text(question_query);
        },
        mouseleave: function () {
            $('#quick-view').text('');
        }
    });

    /**
     * Go to specific question content for tablet/mobile
     */
    function scrollToQuestionsBlock() {
        let position = questions_container.offset().top - 85;
        let windowObj = $(window);

        if (windowObj.width() < 1024) {
            windowObj.scrollTop(position);
        }
    }
})