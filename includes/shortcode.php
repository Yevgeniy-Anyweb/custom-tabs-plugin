<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Custom_Tabs_Plugin_Shortcodes' ) ) {

    class Custom_Tabs_Plugin_Shortcodes {

        public function __construct() {
            add_shortcode( 'custom_tabs_plugin_shortcode', array( $this, 'custom_tabs_shortcode_callback' ) );
            add_action('wp_enqueue_scripts', array($this, 'enqueue_shortcode_styles'));
        }

        public function custom_tabs_shortcode_callback() {
          // Handle the shortcode output
          ob_start();
          $options_tabs = get_option('custom_tabs_plugin_options_tabs', array());
          $options_brands = get_option('custom_tabs_plugin_options_brands', array());

          if (!empty($options_tabs['tabs']) || !empty($options_brands['tabs'])) :
              ?>
              <div class="custom-tabs-plugin">
          
                <div class="tabs-wrapper custom-tabs-plugin__container">
                    <ul id="tabs-nav" class="tabs-nav">
                        <?php if (!empty($options_tabs['tabs']) && is_array($options_tabs['tabs'])) : ?>
                            <?php foreach ($options_tabs['tabs'] as $index => $tab) : ?>
                                <li class="tab <?php echo $index == 0 ? 'active' : '' ?>" data-index="<?php echo $index; ?>">
                                    <span class="tab-name" ><?php echo esc_html($tab['tab_name']); ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>

                    <div id="tab-content-wrapper" class="custom-tabs-plugin__tab-content-wrapper">
                        <?php if (!empty($options_tabs['tabs']) && is_array($options_tabs['tabs'])) : 
         
                          ?>
                            <?php foreach ($options_tabs['tabs'] as $index => $tab) : 
                                           
                              $active = false;  

                              if($index == 0){
                                $active = true;
                              }
                              ?>
                              <div id="tab-<?php echo $index; ?>" data-index="<?php echo $index; ?>" class="tab-content" <?php echo $active ? '' : 'style="display: none;"' ?>>
                                <div class="grid-container">
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
                                                              background-image: url('<?php echo $tab['grid'][0]['left']['bg_image_mobile']; ?>')!important;
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
                                                  <img class="custom-tabs-plugin__container__grid__left-column__person__image" src="<?php echo esc_url($tab['grid'][0]['left']['individual_image']); ?>" alt="individual_image-<?php echo $index ?>" />
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
                                                <img src="<?php echo esc_url($tab['grid'][0]['left']['company_logo']); ?>" alt="company_logo_<?php echo $index ?>"/>
                                              <?php
                                              }
                                              ?>  
                                            </div>
                                          <?php else: ?>
                                            <div class="custom-tabs-plugin__container__grid__left-column__grid-item  custom-tabs-plugin__container__grid__item" >
                                              
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
                                              <div 
                                                class="
                                                  custom-tabs-plugin__container__grid__right-column__grid-item 
                                                  <?php echo $tab['grid'][0]['right']['bottom']['link'] 
                                                  ? '' 
                                                  : 'custom-tabs-plugin__container__grid__right-column-bottom__grid-item__no_link' ?>
                                                  custom-tabs-plugin__container__grid__right-column-bottom__grid-item custom-tabs-plugin__container__grid__item" 

                                                style="background-color: <?php echo esc_attr($tab['grid'][0]['right']['bottom']['bg_color']); ?>"
                                              >

                                              <?php 
                                              if($tab['grid'][0]['right']['bottom']['link']){ ?>
                                                <a> 
                                              <?php 
                                              }
                                              ?>
                                                
                                                  <div><?php echo wp_kses_post($tab['grid'][0]['right']['bottom']['description']); ?></div>
                                                  <?php 
                                                  if($tab['grid'][0]['right']['bottom']['icon']){ ?>
                                                    <img 
                                                      class="custom-tabs-plugin__container__grid__right-column__grid-item__icon" 
                                                      src="<?php echo esc_url($tab['grid'][0]['right']['bottom']['icon']); ?>" alt="icon_<?php echo $index ?>" 
                                                    />
                                                  <?php
                                                  }

                                              if($tab['grid'][0]['right']['bottom']['link']){ ?>
                                                </a> 
                                              <?php 
                                              }
                                              ?>  
                                                
                                              </div>
                                          <?php else: ?>
                                            <div class="custom-tabs-plugin__container__grid__right-column__grid-item  custom-tabs-plugin__container__grid__item" >
                                              
                                            </div>

                                          <?php endif; ?>
                                        </div>
                                    </div>
                                  </div>
                              </div>
         
                              
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                </div>
                <div class="custom_tabs_plugin-brands">
    
          
         
                    <?php
                    if($options_brands['trusted_by']){ ?>
                      <span class="custom_tabs_plugin-brands__title">
                        <?php echo wp_kses_post($options_brands['trusted_by']); ?>
                      </span>
                    <?php }
                    ?>
                  
                    <?php
                    if (!empty($options_tabs['brands']) || !empty($options_brands['brands'])) :
                      ?>
                     
                      <ul class="custom_tabs_plugin-brands__list">
                        <?php foreach ($options_brands['brands'] as $index => $brand) : ?>
                          
                            <li class="custom_tabs_plugin-brands__list_item">
                              <img src="<?php echo esc_url($brand['logo']); ?>" />
                            </li>
                       
                        <?php endforeach; ?>
                      </ul>
            
                    <?php
                    endif;
                    ?>
                  

                </div>
            </div>
              
              <?php
          endif;

          return ob_get_clean();
      }
      public function enqueue_shortcode_styles() {
        wp_enqueue_style('custom-tabs-plugin-style', CUSTOM_TABS_PLUGIN_URL . 'assets/css/front.min.css', array(), CUSTOM_TABS_PLUGIN_VERSION);
        wp_enqueue_style('custom-tabs-plugin-fonts-noto',  'https://use.typekit.net/wuz0gtr.css', array(), CUSTOM_TABS_PLUGIN_VERSION);

        wp_enqueue_script('custom-tabs-plugin-admin-script', CUSTOM_TABS_PLUGIN_URL . 'assets/js/front.bundle.min.js', array('jquery'), CUSTOM_TABS_PLUGIN_VERSION, true);

        
       }
    }
    new Custom_Tabs_Plugin_Shortcodes();
}
