( function( blocks, element, components) {

    var el = element.createElement,
    addFilter = wp.hooks.addFilter,
    createHigherOrderComponent = wp.compose.createHigherOrderComponent,
    useBlockProps = wp.blockEditor.useBlockProps;


    const withYourCustomBlockClass = createHigherOrderComponent(BlockListBlock => {
        return props => {
          const {
            name,
            attributes
          } = props;
          if (name != 'core/paragraph') {
            return el(BlockListBlock, {
              ...props
            });
          }
          const {
            yourAttribute
          } = attributes;
          const customClass = yourAttribute ? 'your-custom-class' : '';
          return el(BlockListBlock, {
            ...props,
            className: customClass
          });
        };
      }, 'withYourCustomBlockClass');

      addFilter('editor.BlockListBlock', 'your-plugin/your-custom-class', withYourCustomBlockClass);

} )( window.wp.blocks, window.wp.element, window.wp.components );
