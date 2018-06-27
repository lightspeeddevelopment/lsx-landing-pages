
<div style="margin-bottom: 12px;">
    {{#if recommended}}<div id="banner_{{_id}}" class="recommended" style="background-color: {{banner_color}};"><strong><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> {{banner_text}}</strong></div>{{/if}}
    <div class="price_table_container">
        <div class="price_table_heading">
            <input placeholder="Heading" type="text" name="label" value="{{label}}" style="width:100%; text-align:center;">
        </div>
        <div class="price_table_body">
            <div class="price_table_row cost" id="bg_color_{{_id}}" style="background-color: {{bg_color}};color: {{text_color}};">
                 <input placeholder="Price" type="text" name="price" value="{{price}}" style="width:100px; text-align:right;"><span><input placeholder="Term" type="text" name="rate" value="{{rate}}" style="width:100px; text-align:left;"></span>
            </div>
            {{#each option}}                
                <div class="price_table_row" id="option_row_{{_id}}" style="position:relative;">                    
                    <a class="lsx-wrapper-remover" data-remove-element="#option_row_{{_id}}" data-confirm="Remove this option?" style="margin-top: 16px;"><span class="dashicons dashicons-no-alt"></span></a>
                    {{:node_point}}
                    <input placeholder="Label" type="text" name="{{:name}}[label]" value="{{label}}" style="text-align:center;">
                    {{#if ../show_labels}}<input placeholder="Value" type="text" name="{{:name}}[value]" value="{{value}}" style="text-align:center;">{{/if}}
                </div>
            {{/each}}
            <div class="price_table_row">
                <button type="button" class="button button-small" data-add-node="option">Add Option</button><br>
                <label><input type="checkbox" name="show_labels" value="1" {{#if show_labels}}checked="checked"{{/if}} data-live-sync="true"> Show Values</label>
            </div>
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

    <hr>
    <div>
        <div><label class="pricing-lable">Price Background</label><input type="text" class="color-field" name="bg_color" value="{{bg_color}}" data-target="#bg_color_{{_id}}" data-style="background-color"></div>
        <div><label class="pricing-lable">Price Text</label><input type="text" class="color-field" name="text_color" value="{{text_color}}" data-target="#bg_color_{{_id}}" data-style="color"></div>

        <div><label class="pricing-lable">Signup Area</label>
            <select name="area_type" data-live-sync="true">
                <option value="button" {{#is area_type value="button"}}selected="selected"{{/is}}>Button</option>
                <option value="shortcode" {{#is area_type value="shortcode"}}selected="selected"{{/is}}>Shortcode</option>
            </select>
        </div>

        <div style="{{#is area_type value="shortcode"}}display:none;{{/is}}">
            <div><label class="pricing-lable">Signup Background</label><input type="text" class="color-field" name="signup_bg" value="{{signup_bg}}" data-target="#signup_bg_{{_id}}" data-style="background-color"></div>
            <div><label class="pricing-lable">Signup Text Color</label><input type="text" class="color-field" name="signup_color" value="{{signup_color}}" data-target="#signup_bg_{{_id}}" data-style="color"></div>
            <div><label class="pricing-lable">Signup Text</label><input type="text" name="signup_text" value="{{#if signup_text}}{{signup_text}}{{else}}Sign Up{{/if}}" data-live-sync="true"></div>
        </div>

        <div style="{{#is area_type value="button"}}display:none;{{/is}}">
            <div><label class="pricing-lable">Signup Shortcode</label><input type="text" name="signup_text" value="{{signup_text}}" data-live-sync="true"></div>
        </div>


        <hr>
        <label><input type="checkbox" name="recommended" value="1" {{#if recommended}}checked="checked"{{/if}} data-live-sync="true"> Feature Banner</label>
        
        <div style="{{#unless recommended}}display: none;{{/unless}}">
            <hr>
            <div><label class="pricing-lable">Banner Background</label><input type="text" class="color-field" name="banner_color" value="{{#if banner_color}}{{banner_color}}{{else}}#ff3a3a{{/if}}" data-target="#banner_{{_id}}" data-style="background"></div>
            <div><label class="pricing-lable">Banner Text</label><input type="text" name="banner_text" value="{{#if banner_text}}{{banner_text}}{{else}}Recommended{{/if}}" data-live-sync="true"></div>
        </div>
       
    </div>