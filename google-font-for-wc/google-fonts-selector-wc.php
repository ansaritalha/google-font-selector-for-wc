<?php
/*
Plugin Name: Google Fonts Selector for WooCommerce
Description: Add a Google Fonts selector to any WooCommerce product. Perfect for personalised products.
Version: 1.0
Author: Offbyte
Author URI: https://offbyte.io
Text Domain: gfs-wc
Domain Path: /languages
*/

require_once plugin_dir_path(__FILE__) . 'includes/gfs-functions.php';

//my code
function my_custom_field_template_path($path, $field) {
    // Define the plugin directory path
    $plugin_path = plugin_dir_path(__FILE__); 

    // Check the field type to apply the custom template
    if ($field->type === 'image-swatch') {
        $path = plugin_dir_path(__FILE__) . 'includes/image-swatch-field.php';
    }
    // Add more conditions if needed
    return $path;
}

add_filter('wapf/field_template_path', 'my_custom_field_template_path', 10, 2);

function custom_inline_scripts_and_styles() {
    // Check if we're on a single product page
    if ( is_product() ) {
        ?>
        <!-- /*my css*/ -->
  
<style>
    .custom-select-wrapper {
    position: relative;
    display: inline-block;
    width: 100%; /* Adjust as needed */
    z-index: 999;
}

.custom-select {
    position: relative;
    display: flex;
    align-items: center;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background: #fff;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: border-color 0.3s, box-shadow 0.3s;
    font-family: Figtree, sans-serif;
}

.custom-select::after {
    content: '▼'; /* Unicode arrow */
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #eb2b6c;
    pointer-events: none;
}

.custom-select-menu {
    display: none; /* Hide the dropdown by default */
    position: absolute;
    background-color: #fff;
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 4px;
    width: 100%;
    z-index: 1000;
    max-height: 300px; /* Optional: limit height and enable scrolling */
    overflow-y: auto; /* Enable scrolling if needed */
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.custom-select-item {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #ddd;
    font-size: 20px;
    transition: background 0.2s;
    color:black;
}

.custom-select-item:hover {
    background: #f0f0f0;
}

.custom-select-field {
    display: none; /* Hide the default select */
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: border-color 0.3s, box-shadow 0.3s;
    width: 100%;
    max-width: 300px; /* Limit width */
    cursor: pointer;
    appearance: none; /* Remove default styling */
}

.custom-select-field:focus {
    border-color: #0073aa;
    box-shadow: 0 2px 4px rgba(0, 115, 170, 0.3);
    outline: none;
}

.custom-select-field::after {
    content: '▼'; /* Unicode arrow */
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: #0073aa;
}

.custom-text-field input[type="text"] {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: border-color 0.3s, box-shadow 0.3s;
    flex: 1; /* Allow text field to grow and fill available space */
}

.custom-text-field input[type="text"]:focus {
    border-color: #0073aa;
    box-shadow: 0 2px 4px rgba(0, 115, 170, 0.3);
    outline: none;
}

input#ribbon-text {
    background: #fff!important;
    color: #000 !important;
    width: 200px !important;
    text-align: center;
}
.custom-text-field input[type="text"]::placeholder {
    color: black; /* Set placeholder text color to black */
}
.custom-field-container {
    margin: 20px 0; /* Optional: margin for spacing */
}

.field-heading {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px; /* Space between heading and fields */
}

.fields-wrapper {
    display: flex;
    gap: 40px;
    margin: 20px 0;
}

@media (max-width: 767px) {
    input#ribbon-text {
        width: 100% !important;
        top: 12px;
        position: relative;
    }
    .fields-wrapper {
        display: block;
    }
    .custom-select-wrapper {
        width: 100%;
        margin-bottom: 20px;
    }
}

@media (min-width: 769px) {
    input#ribbon-text {
        width: 100% !important;
        top: 29px;
        position: relative;
        height:43px;
    }
}
    .custom-select-clear {
        position: relative;
        left: 20px;
        color:#eb2b6c;
    }
.custom-select-search {
    width: calc(100% - 20px);
    padding: 8px 10px;
    margin: 10px; /* Add margin to ensure space around the search bar */
    border: 1px solid #ccc;
    box-sizing: border-box;
    font-size: 14px;
    outline: none;
}

.custom-select-items-container {
    max-height: 100%; /* Allow it to expand to the full height of the dropdown menu */
    overflow-y: auto; /* Enable scroll if content exceeds max height */
}

.tooltip-icon {
    display: inline-block;
    width: 17px;
    height: 17px;
    line-height: 17px;
    text-align: center;
    border-radius: 50%;
    font-size: 12px;
    color: #fff;
   background-color: #eb2b6c;
    border: 1px solid #d9534f;
    cursor: pointer;
    position: absolute;
    top: 0px;
    right: 0;
    z-index: 10;
}

.tooltip-icon::after {
    content: attr(title);
    position: absolute;
    left: 50%;
    bottom: 100%;
    transform: translateX(-50%);
    background: #333;
    color: #fff;
    padding: 4px 8px;
    border-radius: 4px;
    white-space: nowrap;
    font-size: 12px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2); /* Add a subtle shadow for better visibility */
}

.tooltip-icon:hover::after {
    opacity: 1;
    visibility: visible;
}
span.selection {
    visibility: hidden;
}
    .custom-select-text{
        color:black;
    }
    .custom-dropdown,.custom-text-field{
        width:100%;
    }
</style>

        <!-- Inline JavaScript -->
  <script>
document.addEventListener('DOMContentLoaded', function () {
    const selectWrapper = document.querySelector('.custom-select-wrapper');
    const selectField = document.querySelector('#select_fontfamily');
    const ribbonText = document.getElementById('ribbon-text');

    if (!selectWrapper || !selectField) return;

    selectField.style.display = 'none';

    const customSelect = document.createElement('div');
    customSelect.className = 'custom-select';
    const placeholder = 'Selecteer een lettertype';
    const customSelectText = document.createElement('span');
    customSelectText.className = 'custom-select-text';
    customSelectText.textContent = placeholder;

    customSelect.innerHTML = `
        <span class="custom-select-clear" title="Clear selection">&times;</span>
    `;

    customSelect.prepend(customSelectText);

    const customSelectMenu = document.createElement('div');
    customSelectMenu.className = 'custom-select-menu';

    const searchInput = document.createElement('input');
    searchInput.className = 'custom-select-search';
    searchInput.placeholder = 'Search fonts...';
    customSelectMenu.appendChild(searchInput);

    const itemsContainer = document.createElement('div');
    itemsContainer.className = 'custom-select-items-container';
    customSelectMenu.appendChild(itemsContainer);

    let linkElements = {};
    let selectedFontFamily = '';

    // Function to load font CSS files in batches with a delay
    function loadFontsInBatches(fonts, delay) {
        let index = 0;

        function loadNextFont() {
            if (index >= fonts.length) return; // No more fonts to load

            const font = fonts[index];
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = font.src;
            link.onload = function () {
                console.log('Font loaded:', font.name);
                // Update the font-family of items once the font is loaded
                Array.from(itemsContainer.children).forEach(item => {
                    if (item.dataset.src === font.src) {
                        item.style.fontFamily = font.name;
                    }
                });
                index++;
                setTimeout(loadNextFont, delay); // Load the next font after the specified delay
            };
            link.onerror = function () {
                console.error('Failed to load font:', font.name);
                index++;
                setTimeout(loadNextFont, delay); // Try loading the next font after the delay
            };
            document.head.appendChild(link);
            linkElements[font.src] = link;
        }

        loadNextFont(); // Start loading fonts
    }

    // Prepare font data for batch loading
    const fontsToLoad = Array.from(selectField.options)
        .filter(option => option.dataset.src)
        .map(option => ({
            name: option.text,
            src: option.dataset.src
        }));

    // Load fonts in batches with a 1-second delay
    loadFontsInBatches(fontsToLoad, 300);

    // Populate custom select menu with font options
    Array.from(selectField.options).forEach(option => {
        if (option.value) {
            const item = document.createElement('div');
            item.className = 'custom-select-item';
            item.textContent = option.text;
            item.dataset.value = option.value;
            item.dataset.src = option.dataset.src;
            item.style.fontFamily = 'inherit';

            item.addEventListener('click', function () {
                console.log('Font clicked:', item.textContent);
                selectField.value = item.dataset.value;
                customSelectText.textContent = item.textContent;
                customSelectText.style.fontFamily = item.style.fontFamily;
                customSelectMenu.style.display = 'none';

                selectedFontFamily = item.style.fontFamily;
                applyFontToRibbonText();
            });

            itemsContainer.appendChild(item);
        }
    });

    selectWrapper.appendChild(customSelect);
    selectWrapper.appendChild(customSelectMenu);

    const tooltipIcon = document.createElement('span');
    tooltipIcon.className = 'tooltip-icon';
    tooltipIcon.textContent = '?';
    tooltipIcon.title = 'Scroll slowly on dropdown to see a font preview';

    selectWrapper.appendChild(tooltipIcon);

    customSelect.addEventListener('click', function () {
        const isVisible = customSelectMenu.style.display === 'block';
        customSelectMenu.style.display = isVisible ? 'none' : 'block';
        if (!isVisible) {
            searchInput.focus();
        }
    });

    customSelect.querySelector('.custom-select-clear').addEventListener('click', function () {
        selectField.value = '';
        customSelectText.textContent = placeholder;
        customSelectText.style.fontFamily = '';
        searchInput.value = '';
        Array.from(itemsContainer.children).forEach(item => {
            item.style.display = 'block';
        });
        customSelectMenu.style.display = 'none';

        selectedFontFamily = '';
        applyFontToRibbonText();
    });

    searchInput.addEventListener('input', function () {
        const searchValue = searchInput.value.toLowerCase();
        Array.from(itemsContainer.children).forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchValue) ? 'block' : 'none';
        });
    });

    document.addEventListener('click', function (event) {
        if (!selectWrapper.contains(event.target)) {
            customSelectMenu.style.display = 'none';
            searchInput.value = '';
            Array.from(itemsContainer.children).forEach(item => {
                item.style.display = 'block';
            });
        }
    });

    function applyFontToRibbonText() {
        if (ribbonText && selectedFontFamily) {
            ribbonText.style.setProperty('font-family', selectedFontFamily, 'important');
        } else {
            ribbonText.style.setProperty('font-family', '', 'important');
        }
    }

    ribbonText.addEventListener('input', applyFontToRibbonText);
});

</script>

        <?php
    }
}
add_action('wp_footer', 'custom_inline_scripts_and_styles');