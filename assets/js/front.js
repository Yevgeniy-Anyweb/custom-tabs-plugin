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
});