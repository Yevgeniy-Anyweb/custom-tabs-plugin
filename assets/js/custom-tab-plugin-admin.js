jQuery(document).ready(function($) {

  $('#tabs-nav .tab:not(#add-tab-button)').on('click', function() {
    if(!$(this).hasClass('active')){
      let tabId = $(this).data('index');

      // Remove 'active' class from all tab headers
      $('#tabs-nav .tab').removeClass('active');
  
      // Add 'active' class to the clicked tab header
      $(this).addClass('active');
  
      // Hide all tab content
      $('.tab-content').hide();
  
      // Show the corresponding tab content based on tabId
      $('#tab-' + tabId).show();
    }

}); 

  // Add Tab Button Click Event
  $('.add-tab').on('click', function() {
      $('#add-tab-popup').fadeIn(); // Show popup
  });

  // Handle form submission for adding new tab
  $('#add-tab-form').on('submit', function(e) {
      e.preventDefault();
      let tabTitle = $('#tab-title').val();

      let data = {
          action: 'add_tab',
          security: customTabsAjax.nonce,
          new_tab_name: tabTitle
      };

      $.post(customTabsAjax.ajaxurl, data, function(response) {
          if (response.success) {
              let newIndex = response.data.new_tab_index;
              let newTabHTML = '<li class="tab active" data-index="' + newIndex + '"><span class="tab-name">' + tabTitle + '</span><span class="remove-tab" data-index="' + newIndex + '">x</span></li>';
              $('.tabs-nav .tab.active').removeClass('active')
              $('#add-tab-button').before(newTabHTML);
              $('#add-tab-popup').fadeOut(); // Hide popup after adding tab
              $('#tab-title').val(''); // Clear input field
              
              // Add tab content and input field
              $('#tab-content-wrapper').append(response.data.tab_content);

              // Initialize tab content display
              $('.tab-content').hide();
              $('#tab-' + newIndex).show();
          } else {
              alert('Error adding tab. Please try again.');
          }
      });
  });

  // Cancel button in popup
  $('.cancel-popup').on('click', function() {
      $('#add-tab-popup').fadeOut(); // Hide popup
  });

  // Remove Tab Event
  $(document).on('click', '.remove-tab', function() {
      let tabIndex = $(this).data('index');

      // Remove tab from UI immediately
      $('.tab[data-index="' + tabIndex + '"]').remove();
      $('#tab-' + tabIndex).remove(); // Remove tab content from UI
      if($('.tab[data-index="' + (tabIndex - 1) + '"]').length > 0){
        $('.tab[data-index="' + (tabIndex - 1) + '"]').addClass('active');
        $('#tab-' + (tabIndex - 1)).fadeIn()
      } 
      let data = {
          action: 'remove_tab',
          security: customTabsAjax.nonce,
          tab_index: tabIndex
      };

      $.post(customTabsAjax.ajaxurl, data, function(response) {
          if (!response.success) {
              alert('Error removing tab. Please try again.');
              // Optionally, you might want to add the removed tab back to the UI on failure
          }
      }).fail(function() {
          alert('Error removing tab. Please try again.');
          // Optionally, you might want to add the removed tab back to the UI on failure
      });
  });
 
  function initTinyMCE(selector) {
    tinymce.init({
      selector: selector,
      menubar: false,
      toolbar: 'bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent',
      height: 150
    });
  }

  $(document).on('click', '.custom-image-upload', function(e) {
    e.preventDefault();

    let button = $(this);
    let input = button.siblings('.custom-image-url');
    let img = button.siblings('.custom-image-preview');

    // Create the media frame.
    let frame = wp.media({
      title: 'Select or Upload Media',
      button: {
        text: 'Use this media'
      },
      multiple: false  // Set to true if you want to allow multiple images to be selected
    });

    // When an image is selected in the media frame...
    frame.on('select', function() {
      let attachment = frame.state().get('selection').first().toJSON();
      input.val(attachment.url);
      img.attr('src', attachment.url);
    });

    // Finally, open the media frame.
    frame.open();
  });

  // Initialize TinyMCE for existing textareas
  initTinyMCE('.big-text');


  $(document).on('click', '.custom-tabs-plugin__container__grid__item', function() {
    let gridItem = $(this);
    let gridType;
    let tabContent = gridItem.closest('.tab-content')

    if (gridItem.closest('.custom-tabs-plugin__container__grid__left-column').length) {
        gridType = 'left';
    } else if (gridItem.closest('.custom-tabs-plugin__container__grid__right-column').length) {
        if (gridItem.is(':nth-child(1)')) {
            gridType = 'right_top';
        } else if (gridItem.is(':nth-child(2)')) {
            gridType = 'right_bottom';
        }
    }
               

    if (gridType === 'left') {
        // Populate left column pop-up fields and show it
        
        $('#left-column-tab-index').val(tabContent.data('index'));
     
        $('#left-bg-color').val(tabContent.find('.left-bg-color-input').val());

        $('#left-bg-image').val(tabContent.find('.left-bg-image-input').val());
        $('#left-bg-image ~ img').attr('src', tabContent.find('.left-bg-image-input').val());

        $('#left-bg-image-mobile').val(tabContent.find('.left-bg-image-mobile-input').val());
        $('#left-bg-image-mobile ~ img').attr('src', tabContent.find('.left-bg-image-mobile-input').val());

        
        $('#left-comment').val(tabContent.find('.left-comment-input').val());
        tinymce.get('left-comment').setContent(tabContent.find('.left-comment-input').val());

        $('#left-individual-image').val(tabContent.find('.left-individual-image-input').val());
        $('#left-individual-image ~ img').attr('src', tabContent.find('.left-individual-image-input').val());

        $('#left-individual-position').val(tabContent.find('.left-individual-position-input').val());
        $('#left-individual-name').val(tabContent.find('.left-individual-name-input').val());
        $('#left-company-logo').val(tabContent.find('.left-company-logo-input').val());
        $('#left-company-logo ~ img').attr('src', tabContent.find('.left-company-logo-input').val());

        $('#left-rich-text').val(tabContent.find('.left-rich-text-input').val());
 
        $('#left-column-popup').fadeIn();
    } else if (gridType === 'right_top') {
      
        // Populate right column top pop-up fields and show it
        $('#right-top-column-tab-index').val(tabContent.data('index'));
        $('#right-top-bg-color').val(tabContent.find('.right-top-bg-color-input').val());
        $('#right-top-title').val(tabContent.find('.right-top-title-input').val());
        $('#right-top-description').val(tabContent.find('.right-top-description-input').val());
        $('#right-top-popup').fadeIn();
    } else if (gridType === 'right_bottom') {
        // Populate right column bottom pop-up fields and show it
        $('#right-bottom-column-tab-index').val(tabContent.data('index'));
        $('#right-bottom-bg-color').val(tabContent.find('.right-bottom-bg-color-input').val());
        $('#right-bottom-description').val(tabContent.find('.right-bottom-description-input').val());
        $('#right-bottom-icon').val(tabContent.find('.right-bottom-icon-input').val());
        $('#right-bottom-icon ~ img').attr('src', tabContent.find('.right-bottom-icon-input').val());

        tinymce.get('right-bottom-description').setContent(tabContent.find('.right-bottom-description-input').val());

        $('#right-bottom-link').val(tabContent.find('.right-bottom-link-input').val());
        
        $('#right-bottom-popup').fadeIn();
    }
  });

  // Handle form submission for left column grid item
  $('#left-column-form').on('submit', function(e) {
      e.preventDefault();
      
      let formDataArray = $(this).serializeArray();
      let formData = {};
      $.each(formDataArray, function() {
          formData[this.name] = this.value;
      });
      let tabContent = $(`.tab-content[data-index="${formData['left-column-tab-index']}"]`);
      tabContent.find('.left-bg-color-input').val(formData['left_bg_color']);
      tabContent.find('.left-bg-image-input').val(formData['left-bg-image']);
      tabContent.find('.left-bg-image-mobile-input').val(formData['left-bg-image-mobile']);
      tabContent.find('.left-comment-input').val(formData['left_comment']);
      tabContent.find('.left-individual-image-input').val(formData['left-individual-image']);
      tabContent.find('.left-individual-position-input').val(formData['left_individual_name']);
      tabContent.find('.left-individual-name-input').val(formData['left_individual_position']);
      tabContent.find('.left-company-logo-input').val(formData['left-company-logo']);
 

      let html = generateLeftColumnGridItem(formData, formData['left-column-tab-index'])
      if(html){
        tabContent.find('.custom-tabs-plugin__container__grid__left-column').html(html)
      }
      $('#left-column-popup').fadeOut();
  });

  // Handle form submission for right column top grid item
  $('#right-top-form').on('submit', function(e) {
    e.preventDefault();


    let formDataArray = $(this).serializeArray();
    let formData = {};
    $.each(formDataArray, function() {
        formData[this.name] = this.value;
    });
    let tabContent = $(`.tab-content[data-index="${formData['right-top-column-tab-index']}"]`);

    // Update hidden input fields with new data
    tabContent.find('.right-top-bg-color-input').val(formData['right_top_bg_color']);
    tabContent.find('.right-top-title-input').val(formData['right_top_title']);
    tabContent.find('.right-top-description-input').val(formData['right_top_description']);

    let html = generateRightTopGridItem(formData, formData['right-top-column-tab-index'])
    if(html){
      tabContent.find('.custom-tabs-plugin__container__grid__right-column-top__grid-item').remove();
      tabContent.find('.custom-tabs-plugin__container__grid__right-column-bottom__grid-item').before(html)
    }
    // Close popup after saving
    $('#right-top-popup').fadeOut();
});


  // Handle form submission for right column bottom grid item
  $('#right-bottom-form').on('submit', function(e) {
      e.preventDefault();

      let formDataArray = $(this).serializeArray();
      let formData = {};
      $.each(formDataArray, function() {
          formData[this.name] = this.value;
      });
      let tabContent = $(`.tab-content[data-index="${formData['right-bottom-column-tab-index']}"]`);

      tabContent.find('.right-bottom-bg-color-input').val(formData['right_bottom_bg_color']);
      tabContent.find('.right-bottom-description-input').val(formData['right_bottom_description']);
      tabContent.find('.right-bottom-icon-input').val(formData['right-bottom-icon']);
      tabContent.find('.right-bottom-link-input').val(formData['right-bottom-link']);

      let html = generateRightBottomGridItem(formData, formData['right-bottom-column-tab-index'])
      if(html){
        tabContent.find('.custom-tabs-plugin__container__grid__right-column-bottom__grid-item').remove()
        tabContent.find('.custom-tabs-plugin__container__grid__right-column-top__grid-item').after(html)
      }
      $('#right-bottom-popup').fadeOut();
  });

  // Close popup when cancel button is clicked
  $('.cancel-popup, .custom-tabs-plugin__pop-up__overlay').on('click', function() {
      $(this).closest('.custom-tabs-plugin__pop-up').fadeOut();
  });


  //Brands

  let mediaUploader;

    // Add Company button click event
    $('.add-brand').on('click', function(e) {
        e.preventDefault();

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        // Create a new media uploader instance
        mediaUploader = wp.media({
            title: 'Select Logo',
            button: {
                text: 'Use this logo'
            },
            multiple: false
        });

        // When a logo is selected
        mediaUploader.on('select', function() {
            let attachment = mediaUploader.state().get('selection').first().toJSON();
            let logoUrl = attachment.url;

            // Append the new brand to the list with an input field
            let newIndex = $('.custom_tabs_plugin-brands__list_item').length; // Calculate new index
 
            let newBrandItem = $('<li class="custom_tabs_plugin-brands__list_item">' +
                  '<img src="' + logoUrl + '" />' +
                  '<input type="hidden" name="custom_tabs_plugin_options_brands[brands][' + newIndex + '][logo]" value="' + logoUrl + '" />' +
                  '<button class="remove-brand" type="button">&times;</button>' +
              '</li>');
              
            $('.custom_tabs_plugin-brands__list').append(newBrandItem);
            console.log(newBrandItem)
            mediaUploader = null;
        });

        // Open the media uploader
        mediaUploader.open();
    });

    $(document).on('click', '.remove-brand', function() {
      $(this).closest('.custom_tabs_plugin-brands__list_item').remove();

      // Optionally, you might want to update indexes or perform other cleanup tasks
  });
});



function generateLeftColumnGridItem(tab, tab_index) {
  let html;
  if (
      tab['left-bg-image'] !== '' 
    || tab['left-bg-image-mobile']  !== ''
    || tab['left_comment'] !== '' 
    || tab['left-individual-image'] !== '' 
    || tab['left_individual_name']  !== ''
    || tab['left_individual_position'] !== '' 
    || tab['left-company-logo']  !== ''
  ){
    html = `
    <div class="custom-tabs-plugin__container__grid__left-column__grid-item custom-tabs-plugin__container__grid__item" 
        style="${tab['left_bg_color'] ? 'background-color: ' + tab['left_bg_color'] + ';' : ''} 
               ${tab['left-bg-image'] ? 'background-image: url(' + tab['left-bg-image'] + ');' : ''}">
  `;

  if (tab['left-bg-image-mobile']) {
      html += `
          <style>
              @media (max-width: 768px) {
                  .custom-tabs-plugin__container__grid__left-column__grid-item {
                      background-image: url('${tab['left-bg-image-mobile']}')!important;
                  }
              }
          </style>
      `;
  }

  html += `
      <blockquote class="custom-tabs-plugin__container__grid__left-column__comment">
          ${tab['left_comment']}
      </blockquote>
      
  `;

  if (tab['left-individual-image'] && tab['left_individual_position'] && tab['left_individual_name']) {
      html += `
      <div class="custom-tabs-plugin__container__grid__left-column__person">
          <img class="custom-tabs-plugin__container__grid__left-column__person__image" 
              src="${tab['left-individual-image']}" 
              alt="individual_image-${tab_index}" />
          <div class="custom-tabs-plugin__container__grid__left-column__person__info">
            <span class="custom-tabs-plugin__container__grid__left-column__person__info__name">${tab['left_individual_position']}</span>
            <span class="custom-tabs-plugin__container__grid__left-column__person__info__position">${tab['left_individual_name']}</span>
          </div>
        </div>
      `;
  }



  if (tab['left-company-logo']) {
      html += `
          <img src="${tab['left-company-logo']}" 
              alt="company_logo_${tab_index}" />
      `;
  }

  html += `
      </div>
  </div>`;
  }

  

  return html;
}


function generateRightTopGridItem(tab) {
  let html = '';
  if (
      tab['right_top_title'] !== '' 
    || tab['right_top_description']  !== ''
  ) {
      html += '<div class="custom-tabs-plugin__container__grid__right-column__grid-item custom-tabs-plugin__container__grid__right-column-top__grid-item custom-tabs-plugin__container__grid__item"';

      // Check and apply background color if available
      if (tab['right_top_bg_color']) {
          html += ` style="background-color: ${tab['right_top_bg_color']};"`;
      }

      html += `>
        <div class="custom-tabs-plugin__container__grid__right-column-top__grid-item__title">`;
 
      if(tab['right_top_title']){
        html += tab['right_top_title'];
      }
      html += `</div>
        <p>`; 
      if(tab['right_top_title']){
        html += tab['right_top_description'];
      }
      html += `</p>
        </div>`;
  }
  return html;
}


function generateRightBottomGridItem(tab, tab_index) {
  let html = '';
  if (
      tab['right_bottom_description'] !== ''
    || tab['right-bottom-icon'] !== ''
    || tab['right-bottom-link'] !== ''
  ) {
      html += '<div class="custom-tabs-plugin__container__grid__right-column__grid-item  custom-tabs-plugin__container__grid__right-column-bottom__grid-item custom-tabs-plugin__container__grid__item"';
      if (tab['right_bottom_bg_color']) {
        html += ` style="background-color: ${tab['right_bottom_bg_color']};"`;
      }
      html += '><a>'; 
  
 
 


      html += '<p>'; 
      if(tab['right_bottom_description']){
        html += tab['right_bottom_description'];
      }
      html += '</p>';
      if(tab['right-bottom-icon']){
        html += `<img class="custom-tabs-plugin__container__grid__right-column__grid-item__icon" src="${tab['right-bottom-icon']}" alt="icon_${tab_index}" />`;
      }
 
       html += '</a></div>';
  }
  return html;
}

