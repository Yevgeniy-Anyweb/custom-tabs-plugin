<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Custom_Tabs_Options_Page')) {

    class Custom_Tabs_Options_Page {

        public function __construct() {
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_init', array($this, 'page_init'));
            add_action('wp_ajax_add_tab', array($this, 'ajax_add_tab'));
            add_action('wp_ajax_remove_tab', array($this, 'ajax_remove_tab'));
            add_action('wp_ajax_update_tab_name', array($this, 'ajax_update_tab_name')); 
         
        }

        public function add_plugin_page() {
            add_menu_page(
                'Custom Tabs Plugin Settings',
                'Custom Tabs Plugin',
                'manage_options',
                'custom-tabs-plugin',
                array($this, 'create_admin_page')
            );
        }

        public function create_admin_page() {
            ?>
            <div class="wrap">
                <h2>Custom Tabs Plugin Settings</h2>
                <?php settings_errors(); ?>

                <div class="custom-tabs-form">
                    <form method="post" action="options.php">
                        <?php
                        settings_fields('custom_tabs_plugin_options_tabs');
                        $this->render_tabs_settings();
                        do_settings_sections( 'custom_tabs_plugin_options_tabs' );

                        ?>
                        <?php submit_button('Save Settings', 'primary', 'submit', false); ?>
                    </form>
                </div>

                <h2>Brands:</h2>
                <div class="custom-tabs-form">
                    <form method="post" action="options.php">
                        <?php
                        settings_fields('custom_tabs_plugin_options_brands');
                        $this->render_brands_settings();
                        do_settings_sections( 'custom_tabs_plugin_options_brands' );

                        submit_button('Save Brands', 'primary', 'submit_brands', false);
                        ?>
                    </form>
                </div>
            </div>

            <div id="add-tab-popup" class="custom-tabs-plugin__pop-up" style="display: none;">
              <div class="custom-tabs-plugin__pop-up__overlay"></div>
              <div class="custom-tabs-plugin__pop-up__container">
                <h4>Add New Tab</h4>
                <form id="add-tab-form">
                    <label for="tab-title">Tab Title:</label>
                    <input type="text" id="tab-title" name="tab_title">
                    <div>
                      <button type="submit" class="button-primary">Add Tab</button>
                      <button type="button" class="button-secondary cancel-popup">Cancel</button>
                    </div>
                </form>
              </div>
            </div>


            <div id="left-column-popup" class="custom-tabs-plugin__pop-up" style="display: none;">
              <div class="custom-tabs-plugin__pop-up__overlay"></div>
              <div class="custom-tabs-plugin__pop-up__container">
                <h4>Edit Left Column Grid Item</h4>
                <form id="left-column-form">
                      <input type="hidden" id="left-column-tab-index" name="left-column-tab-index">
                      <div>
                        <label for="left-bg-color">Background Color:</label>
                        <input type="color" id="left-bg-color" name="left_bg_color">
                      </div>

                      <div>
                          <label for="left-bg-image">Background Image:</label>
                          <input type="hidden" id="left-bg-image" class="custom-image-url" name="left-bg-image" value="">
                          <img class="custom-image-preview" src="" style="max-width: 100px; height: auto;">
                          <button class="custom-image-upload button">Upload/Add Image</button>
                      </div>

                      <div>
                          <label for="left-bg-image">Background Image Mobile:</label>
                          <input type="hidden" id="left-bg-image-mobile" class="custom-image-url" name="left-bg-image-mobile" value="">
                          <img class="custom-image-preview" src="" style="max-width: 100px; height: auto;">
                          <button class="custom-image-upload button">Upload/Add Image</button>
                      </div>
                      
                      <div>
                        <label for="left-comment">Comment:</label>
                        <textarea class="big-text" id="left-comment" name="left_comment"></textarea>
                      </div>
  
                      <div>
                          <label for="left-individual-image">Individual Image:</label>
                          <input type="hidden" id="left-individual-image" class="custom-image-url" name="left-individual-image" value="">
                          <img class="custom-image-preview" src="" style="max-width: 100px; height: auto;">
                          <button class="custom-image-upload button">Upload/Add Image</button>
                      </div>

                      <div>
                        <label for="left-individual-position">Individual Position:</label>
                        <input type="text" id="left-individual-position" name="left_individual_position">
                      </div>

                      <div>
                        <label for="left-individual-name">Individual Name:</label>
                        <input type="text" id="left-individual-name" name="left_individual_name">
                      </div>

                      <div>
                          <label for="left-company-logo">Company Logo:</label>
                          <input type="hidden" id="left-company-logo" class="custom-image-url" name="left-company-logo" value="">
                          <img class="custom-image-preview" src="" style="max-width: 100px; height: auto;">
                          <button class="custom-image-upload button">Upload/Add Image</button>
                      </div>
  
                      <div>
                        <button type="submit" class="button-primary">Save Changes</button>
                        <button type="button" class="button-secondary cancel-popup">Cancel</button>
                      </div>
                  </form>
                </div>

            </div>
 
      <div id="right-top-popup" class="custom-tabs-plugin__pop-up" style="display: none;">
        <div class="custom-tabs-plugin__pop-up__overlay"></div>
        <div class="custom-tabs-plugin__pop-up__container">
          <h4>Edit Right Top Grid Item</h4>
          <form id="right-top-form">
              <input type="hidden" id="right-top-column-tab-index" name="right-top-column-tab-index">

              <div>
                <label for="right-top-bg-color">Background Color:</label>
                <input type="color" id="right-top-bg-color" name="right_top_bg_color">
              </div>

              <div>
                <label for="right-top-title">Title:</label>
                <input type="text" id="right-top-title" name="right_top_title">
              </div>

              <div>
                <label for="right-top-description">Description:</label>
                <textarea id="right-top-description" name="right_top_description"></textarea>
              </div>

              <div>
                <button type="submit" class="button-primary">Save Changes</button>
                <button type="button" class="button-secondary cancel-popup">Cancel</button>
              </div>
          </form>
        </div>

      </div>

      <!-- Popup form for Right Bottom Grid Item -->
      <div id="right-bottom-popup" class="custom-tabs-plugin__pop-up" style="display: none;">

        <div class="custom-tabs-plugin__pop-up__overlay"></div>
        <div class="custom-tabs-plugin__pop-up__container">
          <h4>Edit Right Bottom Grid Item</h4>
          <form id="right-bottom-form">
            <input type="hidden" id="right-bottom-column-tab-index" name="right-bottom-column-tab-index">

              <div>
                <label for="right-bottom-link">Link:</label>
                <input type="url" id="right-bottom-link" name="right-bottom-link">
              </div>

              <div>
                <label for="right-bottom-bg-color">Background Color:</label>
                <input type="color" id="right-bottom-bg-color" name="right_bottom_bg_color">
              </div>

              <div>
                <label for="right-bottom-description">Description:</label>
                <textarea id="right-bottom-description" class="big-text" name="right_bottom_description"></textarea>
              </div>

              <div>
                <label for="right-bottom-icon">Icon:</label>
                <input type="hidden" id="right-bottom-icon" class="custom-image-url" name="right-bottom-icon" value="">
                <img class="custom-image-preview" src="" style="max-width: 100px; height: auto;">
                <button class="custom-image-upload button">Upload/Add Image</button>
              </div>

              <div>    
                <button type="submit" class="button-primary">Save Changes</button>
                <button type="button" class="button-secondary cancel-popup">Cancel</button>
              </div>
          </form>
        </div>
        
      </div>
    <?php
        }

        public function page_init() {
            // Register settings for Tab 1
            register_setting(
                'custom_tabs_plugin_options_tabs',
                'custom_tabs_plugin_options_tabs',
                array($this, 'sanitize_tabs')
            );

            // Register settings for Additional Information
            register_setting(
                'custom_tabs_plugin_options_brands',
                'custom_tabs_plugin_options_brands',
                array($this, 'sanitize_brands')
            );
        }

        public function sanitize_tabs($input) {
            // Sanitize input for tabs settings here if needed
            if (!empty($input['tabs']) && is_array($input['tabs'])) {
                foreach ($input['tabs'] as &$tab) {
                    $tab['tab_name'] = sanitize_text_field($tab['tab_name']); 
                }
            }
            return $input;
        }

        public function sanitize_brands($input) { 
            $input['trusted_by'] = wp_kses_post($input['trusted_by']); 
            return $input;
        }

        public function render_tabs_settings() {
            $options = get_option('custom_tabs_plugin_options_tabs', array());
            ?>
            <p>
              After creating tabs, you can use shortcode to show them at site, be carefully with thin containers: [custom_tabs_plugin_shortcode]
            </p>
            <div class="tabs-settings custom-tabs-plugin">
                <h3>Tabs Management</h3>
                <div class="tabs-wrapper">
                    <ul id="tabs-nav" class="tabs-nav">
                        <?php if (!empty($options['tabs']) && is_array($options['tabs'])) : ?>
                            <?php foreach ($options['tabs'] as $index => $tab) : ?>
                                <li class="tab <?php echo $index == 0 ? 'active' : '' ?>" data-index="<?php echo $index; ?>">
                                    <span class="tab-name" ><?php echo esc_html($tab['tab_name']); ?></span>
                                    <span class="remove-tab" data-index="<?php echo $index; ?>">x</span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <li class="tab add-tab-row" id="add-tab-button">
                            <button type="button" class="add-tab button-primary">+ Add Tab</button>
                        </li>
                    </ul>

                    <div id="tab-content-wrapper"  >
                        <?php if (!empty($options['tabs']) && is_array($options['tabs'])) : ?>
                            <?php foreach ($options['tabs'] as $index => $tab) : 

                              $active = false;  

                              if($index == 0){
                                $active = true;
                              }

                              echo $this->get_tab_content_html($index, $tab, $active);
                              
                            endforeach; ?>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
            <?php
        }

        public function render_brands_settings() {
            $options_brands = get_option('custom_tabs_plugin_options_brands', array());
            ?>
            <div class="custom_tabs_plugin-brands-settings"> 
                <div class="custom_tabs_plugin-brands">
                  <label for="trusted_by">
                    Text before list of companies: <input type="text" class="trusted_by" id="trusted_by" value="<?php echo wp_kses_post($options_brands['trusted_by']); ?>" name="custom_tabs_plugin_options_brands[trusted_by]" />
                  </label> 
          
                  <ul class="custom_tabs_plugin-brands__list">
                    <?php
                    if (!empty($options_tabs['brands']) || !empty($options_brands['brands'])) :
                      ?>
                     
                      
                        <?php foreach ($options_brands['brands'] as $index => $brand) : ?>
                            <li class="custom_tabs_plugin-brands__list_item">
                              <img src="<?php echo esc_url($brand['logo']); ?>" />
                              <input type="hidden" name="custom_tabs_plugin_options_brands[brands][<?php echo $index; ?>][logo]" value="<?php echo esc_url($brand['logo']); ?>" />
                              <button class="remove-brand" type="button">&times;</button>
                            </li>
                        <?php endforeach; ?>
               
            
                    <?php
                    endif;
                    ?>
                  </ul>
                  <button class="button add-brand">Add Company</button>

                </div>
            </div>
            <?php
        }


        public function ajax_add_tab() {
          check_ajax_referer('custom-tabs-nonce', 'security');
      
          if (isset($_POST['security']) && isset($_POST['action']) && $_POST['action'] === 'add_tab') {
              $options = get_option('custom_tabs_plugin_options_tabs', array());
      
              // Validate and sanitize input as needed
              $new_tab_name = isset($_POST['new_tab_name']) ? sanitize_text_field($_POST['new_tab_name']) : 'New Tab';
      
              if (!is_array($options)) {
                  $options = [];
              }
              // Ensure $options['tabs'] exists and is an array
              if (!isset($options['tabs']) || !is_array($options['tabs'])) {
                  $options['tabs'] = array();
              }
      
              $new_tab_index = count($options['tabs']);
          
              $options['tabs'][] = array(
                  'tab_name' => $new_tab_name,
                  'grid' => array(
                      array(
                          'left' => array(),
                          'right' => array(
                              'top' => array(),
                              'bottom' => array()
                          )
                      )
                  )
              );
      
              update_option('custom_tabs_plugin_options_tabs', $options);
      
              // Prepare response data including tab content
              $tab_content = $this->get_tab_content_html($new_tab_index, $options['tabs'][$new_tab_index], true);
              $response = array(
                  'new_tab_index' => $new_tab_index,
                  'new_tab_name' => $new_tab_name,
                  'tab_count' => count($options['tabs']),
                  'tab_content' => $tab_content
              );
      
              wp_send_json_success($response);
          }
      
          wp_send_json_error();
      }
      
      public function ajax_remove_tab() {
          check_ajax_referer('custom-tabs-nonce', 'security');
      
          if (isset($_POST['tab_index'])) {
              $tab_index = intval($_POST['tab_index']);
      
              $options = get_option('custom_tabs_plugin_options_tabs', array());
      
              if (isset($options['tabs'][$tab_index])) {
                  unset($options['tabs'][$tab_index]);
                  $options['tabs'] = array_values($options['tabs']);
                  update_option('custom_tabs_plugin_options_tabs', $options);
                  
                  // Prepare response data
                  $response = array(
                      'tab_index' => $tab_index,
                      'tab_count' => count($options['tabs'])
                  );
      
                  wp_send_json_success($response);
              }
          }
      
          wp_send_json_error();
      }
 
 

      // Helper function to get tab content HTML
      private function get_tab_content_html($tab_index, $tab, $active = false) {
          ob_start();
 
          $tab_name = $tab['tab_name'];
 
          ?>
          <div id="tab-<?php echo $tab_index; ?>" data-index="<?php echo $tab_index; ?>" class="tab-content" <?php echo $active ? '' : 'style="display: none;"' ?>>
          <label for="tab-title-<?php echo $tab_index ?>">
            Tab Title: <input type="text" class="tab_name_input" id="tab-title-<?php echo $tab_index ?>" value="<?php echo esc_html($tab_name); ?>" name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][tab_name]" />
          </label> 

 
          <input 
            value="<?php echo isset($tab['grid'][0]['left']['bg_color']) ? ($tab['grid'][0]['left']['bg_color']) : '' ?>"
            class="left-bg-color-input" name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][grid][0][left][bg_color]"
            type="hidden" />

          <input 
            value="<?php echo isset($tab['grid'][0]['left']['bg_image']) ? ($tab['grid'][0]['left']['bg_image']) : '' ?>"
            class="left-bg-image-input" 
            name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][grid][0][left][bg_image]" 
            type="hidden" />

          <input 
            value="<?php echo isset($tab['grid'][0]['left']['bg_image_mobile']) ? ($tab['grid'][0]['left']['bg_image_mobile']) : '' ?>"
            class="left-bg-image-mobile-input" 
            name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][grid][0][left][bg_image_mobile]" 
            type="hidden" />

          <input 
            value="<?php echo isset($tab['grid'][0]['left']['comment']) ? wp_kses_post($tab['grid'][0]['left']['comment']) : '' ?>"
            class="left-comment-input" 
            name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][grid][0][left][comment]" 
            type="hidden" />

          <input 
            value="<?php echo isset($tab['grid'][0]['left']['individual_image']) ? ($tab['grid'][0]['left']['individual_image']) : '' ?>"
            class="left-individual-image-input" 
            name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][grid][0][left][individual_image]" 
            type="hidden" />

          <input 
            value="<?php echo isset($tab['grid'][0]['left']['individual_position']) ? ($tab['grid'][0]['left']['individual_position']) : '' ?>"
            class="left-individual-position-input" 
            name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][grid][0][left][individual_position]" 
            type="hidden" />

          <input 
            value="<?php echo isset($tab['grid'][0]['left']['individual_name']) ? ($tab['grid'][0]['left']['individual_name']) : '' ?>"
            class="left-individual-name-input" 
            name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][grid][0][left][individual_name]" 
            type="hidden" />

          <input 
            value="<?php echo isset($tab['grid'][0]['left']['company_logo']) ? ($tab['grid'][0]['left']['company_logo']) : '' ?>"
            class="left-company-logo-input" 
            name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][grid][0][left][company_logo]" 
            type="hidden" /> 



          <input
            value="<?php echo isset($tab['grid'][0]['right']['top']['bg_color']) ? ($tab['grid'][0]['right']['top']['bg_color']) : '' ?>"
            class="right-top-bg-color-input" 
            name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][grid][0][right][top][bg_color]" 
            type="hidden" />

          <input 
            value="<?php echo isset($tab['grid'][0]['right']['top']['title']) ? ($tab['grid'][0]['right']['top']['title']) : '' ?>"
            class="right-top-title-input" 
            name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][grid][0][right][top][title]"  
            type="hidden" />

          <input 
            value="<?php echo isset($tab['grid'][0]['right']['top']['description']) ? ($tab['grid'][0]['right']['top']['description']) : '' ?>"
            class="right-top-description-input" 
            name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][grid][0][right][top][description]"  
            type="hidden" />



          <input
            value="<?php echo isset($tab['grid'][0]['right']['bottom']['bg_color']) ? ($tab['grid'][0]['right']['bottom']['bg_color']) : '' ?>"
            class="right-bottom-bg-color-input" 
            name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][grid][0][right][bottom][bg_color]"  
            type="hidden" />

          <input
            value='<?php echo isset($tab['grid'][0]['right']['bottom']['description']) ? ($tab['grid'][0]['right']['bottom']['description']) : '' ?>' 
            class="right-bottom-description-input" 
            name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][grid][0][right][bottom][description]"  
            type="hidden" />

          <input
            value="<?php echo isset($tab['grid'][0]['right']['bottom']['icon']) ? ($tab['grid'][0]['right']['bottom']['icon']) : '' ?>" 
            class="right-bottom-icon-input" 
            name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][grid][0][right][bottom][icon]"  
            type="hidden" />

          <input
            value="<?php echo isset($tab['grid'][0]['right']['bottom']['link']) ? ($tab['grid'][0]['right']['bottom']['link']) : '' ?>" 
            class="right-bottom-link-input" 
            name="custom_tabs_plugin_options_tabs[tabs][<?php echo $tab_index ?>][grid][0][right][bottom][link]"  
            type="hidden" />

          <div class="grid-container custom-tabs-plugin__container">
                <!-- Grid structure with add blog buttons -->
                <div class="custom-tabs-plugin__container__grid">
                    <div class="custom-tabs-plugin__container__grid__left-column">
                        <?php  
                        if(
                          isset($tab['grid'][0]['left']) 
                          && 
                          (
                             
                               $tab['grid'][0]['left']['bg_image']
                            ||  $tab['grid'][0]['left']['bg_image_mobile']
                            ||  $tab['grid'][0]['left']['comment'] 
                            ||  $tab['grid'][0]['left']['individual_image'] 
                            ||  $tab['grid'][0]['left']['individual_position']
                            ||  $tab['grid'][0]['left']['individual_name']
                            ||  $tab['grid'][0]['left']['company_logo'] 
              
                          ))
                        : ?>
                        <div class="custom-tabs-plugin__container__grid__left-column__grid-item custom-tabs-plugin__container__grid__item" 
                            style="
                                <?php echo $tab['grid'][0]['left']['bg_color'] ? 'background-color: ' . $tab['grid'][0]['left']['bg_color'] . ';' : '' ?> 
                                <?php echo $tab['grid'][0]['left']['bg_image'] ? 'background-image: url(' . $tab['grid'][0]['left']['bg_image'] . ');' : '' ?> 
                            "
                        >

                            <?php if (!empty($tab['grid'][0]['left']['bg_image_mobile'])) : ?>
                                <style>
                                    @media (max-width: 768px) {
                                        .custom-tabs-plugin__container__grid__left-column__grid-item {
                                            background-image: url('<?php echo $tab['grid'][0]['left']['bg_image_mobile']; ?>')!important;;
                                        }
                                    }
                                </style>
                            <?php endif; ?>
                             
                            <blockquote class="custom-tabs-plugin__container__grid__left-column__comment">
                              <?php echo wp_kses_post($tab['grid'][0]['left']['comment']); ?>
                            </blockquote>
                            <div class="custom-tabs-plugin__container__grid__left-column__person">
                              <?php 
                              if($tab['grid'][0]['left']['individual_image']){ ?>
                                <img class="custom-tabs-plugin__container__grid__left-column__person__image" src="<?php echo esc_url($tab['grid'][0]['left']['individual_image']); ?>" alt="individual_image-<?php echo $tab_index ?>" />
                              <?php
                              }
                              ?>  
                              <div class="custom-tabs-plugin__container__grid__left-column__person__info">
                                <span class="custom-tabs-plugin__container__grid__left-column__person__info__name"><?php echo $tab['grid'][0]['left']['individual_position']; ?></span>
                                <span class="custom-tabs-plugin__container__grid__left-column__person__info__position"><?php echo $tab['grid'][0]['left']['individual_name']; ?></span>
                              </div>
                            </div>
                            <?php 
                            if($tab['grid'][0]['left']['company_logo']){ ?>
                              <img src="<?php echo esc_url($tab['grid'][0]['left']['company_logo']); ?>" alt="company_logo_<?php echo $tab_index ?>"/>
                            <?php
                            }
                            ?>  
                          </div>
                        <?php else: ?>
                          <div class="custom-tabs-plugin__container__grid__left-column__grid-item custom-tabs-plugin__container__grid__item_empty custom-tabs-plugin__container__grid__item" >
                            <span></span>
                          </div>
                        <?php endif; ?>
                    </div>
                    <div class="custom-tabs-plugin__container__grid__right-column">

                        
                      <?php 
                        if(
                          isset($tab['grid'][0]['left']) 
                          && 
                          (
                                 
                               $tab['grid'][0]['right']['top']['title']  
                            ||  $tab['grid'][0]['right']['top']['description']  
              
                          ))
                        : ?>
                            <div class="custom-tabs-plugin__container__grid__right-column__grid-item custom-tabs-plugin__container__grid__right-column-top__grid-item custom-tabs-plugin__container__grid__item" style="background-color: <?php echo esc_attr($tab['grid'][0]['right']['top']['bg_color']); ?>">
                                <p class="custom-tabs-plugin__container__grid__right-column-top__grid-item__title"><?php echo wp_kses_post($tab['grid'][0]['right']['top']['title']); ?></p>
                                <p class=""><?php echo wp_kses_post($tab['grid'][0]['right']['top']['description']); ?></p>
                            </div>
                        <?php else: ?>
                          <div class="custom-tabs-plugin__container__grid__right-column__grid-item custom-tabs-plugin__container__grid__item_empty custom-tabs-plugin__container__grid__item" >
                            <span></span>
                          </div>
                        <?php endif; ?>

                        <?php 
                        if(
                          isset($tab['grid'][0]['left']) 
                          && 
                          (
                                $tab['grid'][0]['right']['bottom']['icon'] 
                            ||  $tab['grid'][0]['right']['bottom']['description']  
                            ||  $tab['grid'][0]['right']['bottom']['link']              
                          ))
                        : ?>
                            <div class="custom-tabs-plugin__container__grid__right-column__grid-item <?php echo $tab['grid'][0]['right']['bottom']['link'] ? '' : 'custom-tabs-plugin__container__grid__right-column-bottom__grid-item__no_link' ?> custom-tabs-plugin__container__grid__right-column-bottom__grid-item custom-tabs-plugin__container__grid__item" style="background-color: <?php echo esc_attr($tab['grid'][0]['right']['bottom']['bg_color']); ?>">
                            <?php 
                            if($tab['grid'][0]['right']['bottom']['link']){ ?>
                              <a> 
                            <?php 
                            }
                            ?>
                              
                                <div><?php echo wp_kses_post($tab['grid'][0]['right']['bottom']['description']); ?></div>
                                <?php 
                                if($tab['grid'][0]['right']['bottom']['icon']){ ?>
                                  <img class="custom-tabs-plugin__container__grid__right-column__grid-item__icon" src="<?php echo esc_url($tab['grid'][0]['right']['bottom']['icon']); ?>" alt="icon_<?php echo $tab_index ?>" />
                                <?php
                                }

                            if($tab['grid'][0]['right']['bottom']['link']){ ?>
                              </a> 
                            <?php 
                            }
                            ?>  
                               
                            </div>
                        <?php else: ?>
                          <div class="custom-tabs-plugin__container__grid__right-column__grid-item custom-tabs-plugin__container__grid__item_empty custom-tabs-plugin__container__grid__item" >
                            <span></span>
                          </div>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
          </div>
          <?php
          return ob_get_clean();
      }
      

        public function ajax_update_tab_name() {
            check_ajax_referer('custom-tabs-nonce', 'security');

            if (isset($_POST['tab_index']) && isset($_POST['tab_name'])) {
                $tab_index = intval($_POST['tab_index']);
                $tab_name = sanitize_text_field($_POST['tab_name']);

                $options = get_option('custom_tabs_plugin_options_tabs', array());

                if (isset($options['tabs'][$tab_index])) {
                    $options['tabs'][$tab_index]['tab_name'] = $tab_name;
                    update_option('custom_tabs_plugin_options_tabs', $options);
                    wp_send_json_success();
                }
            }

            wp_send_json_error();
        }

 
    }

    new Custom_Tabs_Options_Page();
}
