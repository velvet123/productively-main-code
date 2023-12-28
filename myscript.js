

// jQuery(document).ready(function ($) {
//     function generateRandomName() {
//         const randomString = Math.random().toString(36).substring(7);
//         return `Productivity_${randomString}.pdf`;
//     }
//     // Function to open the Contact Us form
//     function openContactForm() {
//         return new Promise(function (resolve, reject) {
//             var elementToClick = document.querySelector('[data-id="3688"]');
//             if (elementToClick) {
//                 elementToClick.click(); // Click the element to open the form
//                 setTimeout(() => {
//                     var form = document.querySelector(".wpcf7-form");
//                     var closeButton = document.querySelector(".swal2-close");
//                     if (form) {
//                         form.addEventListener("submit", function (event) {
//                             // You can also submit the form programmatically if needed
//                             // form.submit();
//                             setTimeout(() => {
//                                 closeButton.click()
//                                 resolve();
//                             }, 1000);
//                         });
//                     } else {
//                         reject("Form element not found");
//                     }
//                 }, 2000);
//             } else {
//                 reject("Element to click not found");
//             }
//         });
//     }
//     // Function to generate PDF
//     function generatePDF(ref) {
//         // console.log("i am working");
//         const pdf = new jsPDF("p", "pt", "letter");
//         const source = $(ref).closest(".single-post-by-category-with-download");
//         const convertData = source.html();
//         const fileName = generateRandomName();
//         const specialElementHandlers = {
//             "#bypassme": function (element, renderer) {
//                 return true;
//             },
//         };
//         const margins = {
//             top: 80,
//             bottom: 80,
//             left: 40,
//             width: 522,
//         };
//         pdf.fromHTML(
//             convertData,
//             margins.left,
//             margins.top,
//             {
//                 width: margins.width,
//                 elementHandlers: specialElementHandlers,
//             },
//             function (dispose) {
//                 pdf.save(fileName);
//             },
//             margins
//         );
//     }
//     // Trigger function to open the Contact Us form
//     $("#postsContainer button#post-btn").on("click", function (e) {
//         const ref = this;
//         openContactForm()
//             .then(function () {
//                 // Form submitted, now generate PDF
//                 setTimeout(() => {
//                     generatePDF(ref);
//                 }, 1000);
//             })
//             .catch(function (error) {
//                 console.error("Error: " + error);
//             });
//     });
// });





//**************************** Jquery for post filter************************************************ */
jQuery(document).ready(function ($) {
    // Function to handle click event
    function handleTabClick() {
        var categoryId = $(this).data('category-id');

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'get_single_and_related_posts',
                category_id: categoryId,
            },
            success: function (response) {
                $('.single-post-left').html(response.single_post);
                $('.related-posts-right').html(response.related_posts);
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
    }

    // Trigger click event on the first .category-tab after a delay
    setTimeout(function () {
        $('.category-tab:first').trigger('click');
    });

    // Click event handler for .category-tab elements
    $('.category-tab').on('click', handleTabClick);






    // ********************* Code for active tabs ********************************//


    $(".category-tab").click(function () {
        // Remove active class from all tabs
        $(".category-tab").removeClass("active");
        // Add active class to the clicked tab
        $(this).addClass("active");

        // Hide all tab content
        $(".tab-content").hide();

        // Show the corresponding tab content
        var tabId = $(this).data("tab");
        $("#" + tabId).show();

    });

    // Set the initial active tab (optional)
    $(".category-tab:first").click();


});




//*****************************Code for doenload button from bulder*************************************** */


jQuery(document).ready(function($) {
    // Listen for click event on the anchor tag
    $('#post2-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default action of the anchor tag

        // Trigger the execution of the shortcode
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                'action': 'execute_shortcode'
            },
            success: function(response) {
                // Handle the response if needed
                console.log(response);
            }
        });
    });
});





function resetForm() {
    document.getElementById('your-form-id').reset(); // Replace 'your-form-id' with the actual ID of your form
}











//************************************************************************************* */


jQuery(document).ready(function ($) {
    let specificHref;
    let fileName;
    function downloadPDF() {
      return new Promise((resolve, reject) => {
        const downloadLink = $(".download-clss.popmake-5994");
        const form = $(".wpcf7-form");
        downloadLink.on("click", function (event) {
          event.preventDefault();
          specificHref = $(this).attr("href").split(" ")[0];
          fileName = specificHref.split("/").pop();
          form.on("wpcf7mailsent", function () {
            const closeButton = $(".pum-close");
            closeButton.click();
            resolve();
          });
        });
      });
    }
    downloadPDF()
      .then((result) => {
          var downloadLink = $("<a>", {
            href: specificHref,
            download: fileName,
          })
            .hide()
            .appendTo("body");
          downloadLink[0].click();
          downloadLink.remove();
      })
      .catch((error) => {
        console.error(error);
      });
  });





//   jQuery(document).ready(function($) {
//     // Add the class 'popmake-6608' to the div with class 'ast-custom-button'
//     $('.ast-custom-button').addClass('popmake-6608');
// });