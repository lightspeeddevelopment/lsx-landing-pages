    <a class="lsx-wrapper-remover" style="left: -9px;"

    class="lsx-add-element"
    data-title="<?php echo esc_attr( 'Configure Pricing Table' ); ?>"
    data-height="750"
    data-width="500"

    data-before="lsx_content_box_bind"

    data-modal="{{_node_point}}.config"
    data-template="config_pricing_table"
    data-focus="true"
    data-buttons="save delete"
    data-footer="conduitModalFooter"

    ><span class="dashicons dashicons-edit"></span></a>
    Pricing Table {{config/label}}
    {{#with sconfig}}
        <div style="margin-bottom: 12px;">
            {{#if recommended}}<div id="banner_{{_id}}" class="recommended" style="background-color: {{banner_color}};"><strong><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> {{banner_text}}</strong></div>{{/if}}
            <div class="price_table_container">
                <div class="price_table_heading">
                   {{label}}
                </div>
                <div class="price_table_body">
                    <div class="price_table_row cost" id="bg_color_{{_id}}" style="background-color: {{bg_color}};color: {{text_color}};">
                        <strong>{{price}}</strong> <span>{{rate}}</span>
                    </div>
                    {{#each option}}                
                        <div class="price_table_row" id="option_row_{{_id}}" style="position:relative;">                    
                            {{label}}
                            {{#if ../show_labels}}<div>{{value}}</div>{{/if}}
                        </div>
                    {{/each}}

                </div>
                {{#is area_type value="button"}}
                    <a href="#" id="signup_bg_{{_id}}" class="price_table_signup btn btn-primary btn-lg btn-block" style="background-color: {{signup_bg}};color: {{signup_color}};">
                {{/is}}
                    {{#if signup_text}}{{signup_text}}{{else}}Sign Up{{/if}}
                {{#is area_type value="button"}}
                    </a>
                {{/is}}
            </div>
        </div>
    {{/with}}

<div style="clear: both;"></div>
