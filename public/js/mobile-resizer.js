// $(function() {
//     let left_margin = $('.left-white-space');
//     let isMobileActive = false;
//     let titles = $('.qualification-title');
//     let question_panel_margin = $('.question-panel-margin');

//     function resulutionMalipulator(isWindowInitialized = false) {
//         let width = $(window).width();
//         let duration = 300;
        
//         if (isWindowInitialized === false)
//             duration = 0;
    
//         if (width < 777 && !isMobileActive) {
//           left_margin.addClass('left-white-space-mobile', duration);
//           titles.addClass('title-mobile', duration);
//           question_panel_margin.addClass('question-panel-margin-mobile');
    
//           isMobileActive = true;
//         } else if (width >= 777 && isMobileActive) {
//           left_margin.removeClass('left-white-space-mobile', duration);
//           titles.removeClass('title-mobile', duration);
//           question_panel_margin.removeClass('question-panel-margin-mobile');
                
//           isMobileActive = false;
//         }
//     }
    
//     resulutionMalipulator();

//     $(window).resize(function() {
//         resulutionMalipulator(true);
//     });
// });