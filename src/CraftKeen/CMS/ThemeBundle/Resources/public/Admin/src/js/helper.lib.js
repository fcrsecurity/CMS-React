/**
 * Created by mikalai on 06/07/17.
 */

/**
 *
 * @type {{textToSlug: Helper.textToSlug}}
 */
var Helper = {
    /**
     * Converts text into a slug compatible string
     * @param text
     * @returns {string}
     */
    textToSlug: function(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Replace spaces with -
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start of text
            .replace(/-+$/, '');            // Trim - from end of text
    }
};