# WordPress Block Edit/Save Patterns

This reference provides standard patterns for implementing dynamic content in `edit.js` and `save.js`.

---

## 1. RichText (Editable Text)

Used for headings, paragraphs, or any inline-editable string.

### `edit.js`
```jsx
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
    const { title } = attributes;
    const blockProps = useBlockProps();

    return (
        <div { ...blockProps }>
            <RichText
                tagName="h2"
                value={ title }
                onChange={ ( newTitle ) => setAttributes( { title: newTitle } ) }
                placeholder="Enter title here..."
                className="my-custom-heading-class"
            />
        </div>
    );
}
```

### `save.js`
```jsx
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
    const { title } = attributes;
    const blockProps = useBlockProps.save();

    return (
        <div { ...blockProps }>
            <RichText.Content
                tagName="h2"
                value={ title }
                className="my-custom-heading-class"
            />
        </div>
    );
}
```

---

## 2. InnerBlocks (Nested Arbitrary Blocks)

Used when a block acts as a container for other blocks.

### `edit.js`
```jsx
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

const ALLOWED_BLOCKS = [ 'core/heading', 'core/paragraph', 'core/image' ];
const TEMPLATE = [
    [ 'core/heading', { placeholder: 'Enter heading...' } ],
    [ 'core/paragraph', { placeholder: 'Enter content...' } ]
];

export default function Edit() {
    return (
        <div { ...useBlockProps() }>
            <div className="my-container-class">
                 <InnerBlocks
                    allowedBlocks={ ALLOWED_BLOCKS }
                    template={ TEMPLATE }
                />
            </div>
        </div>
    );
}
```

### `save.js`
```jsx
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function save() {
    return (
        <div { ...useBlockProps.save() }>
            <div className="my-container-class">
                <InnerBlocks.Content />
            </div>
        </div>
    );
}
```

---

## 3. MediaUpload (Images)

Used for allowing users to select or upload images.

### `edit.js`
```jsx
import { useBlockProps, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
    const { imageUrl, imageAlt, imageId } = attributes;

    const onSelectImage = ( media ) => {
        setAttributes( {
            imageUrl: media.url,
            imageId: media.id,
            imageAlt: media.alt || ''
        } );
    };

    return (
        <div { ...useBlockProps() }>
            { ! imageUrl ? (
                <MediaUploadCheck>
                    <MediaUpload
                        onSelect={ onSelectImage }
                        allowedTypes={ [ 'image' ] }
                        value={ imageId }
                        render={ ( { open } ) => (
                            <Button variant="secondary" onClick={ open }>
                                Select Image
                            </Button>
                        ) }
                    />
                </MediaUploadCheck>
            ) : (
                <img src={ imageUrl } alt={ imageAlt } className="my-image-class" />
                // Note: Consider adding a way to replace/remove the image via a toolbar button here
            ) }
        </div>
    );
}
```

### `save.js`
```jsx
import { useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
    const { imageUrl, imageAlt } = attributes;

    return (
        <div { ...useBlockProps.save() }>
            { imageUrl && (
                <img
                    src={ imageUrl }
                    alt={ imageAlt }
                    className="my-image-class"
                />
            ) }
        </div>
    );
}
```

---

## 4. InspectorControls (Sidebar Settings)

Used for block-level configuration options (e.g., toggles, dropdowns).

### `edit.js`
```jsx
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
    const { showButton } = attributes;

    return (
        <div { ...useBlockProps() }>
            <InspectorControls>
                <PanelBody title="Block Settings">
                    <ToggleControl
                        label="Show Call to Action Button"
                        checked={ showButton }
                        onChange={ ( value ) => setAttributes( { showButton: value } ) }
                    />
                </PanelBody>
            </InspectorControls>
            
            <div className="my-block-content">
                <p>Always visible content</p>
                { showButton && <button>Call to Action</button> }
            </div>
        </div>
    );
}
```

### `save.js`
```jsx
import { useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
    const { showButton } = attributes;

    return (
        <div { ...useBlockProps.save() }>
             <div className="my-block-content">
                <p>Always visible content</p>
                { showButton && <button>Call to Action</button> }
            </div>
        </div>
    );
}
```
