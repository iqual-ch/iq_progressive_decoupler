# iq_progressive_decoupler

Module that provides progressive decoupling for drupal 8 with ui patterns.
Uses twigjs (https://github.com/twigjs/twig.js) for rendering in browser.

Includes **DecoupledBlockBase**: A block base class to be used for creating block plugins that make use of decoupling. It provides pattern selection for each block instance and stores pattern-related data (e.g. its twig code) into drupalSettings to make it accessible in the frontend.


 **Submodules**
- iq_progressive_decoupler_rest_block
A block that asynchronously loads data from a REST endpoint and renders with a pattern
