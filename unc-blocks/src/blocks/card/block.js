/**
 * BLOCK: card
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

const {
  RichText,
  MediaUpload,
  MediaUploadCheck
} = wp.blockEditor || wp.editor;

const ALLOWED_MEDIA_TYPES = [ 'image' ];

const { Button } = wp.components;

const { withState } = wp.compose;

const attributes = {
	cardHeader: {
		type: 'array',
		source: 'children',
		selector: '.card-header'
	},
	cardContent: {
		type: 'array',
		source: 'children',
		selector: '.card-content'
  },
  imgURL: {
		type: 'string',
		default: ''
	},
	imgID: {
		type: 'number'
	},
	imgAlt: {
		type: 'string',
		default: ''
	}
};

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType('unc/card-block', {
	title: __('Card'),
  icon: 'id',
	category: 'common',
	keywords: [__('Stat'), __('Feature'), __('UNC Blocks')],
	attributes,

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit: withState({ editable: '' })(function(props) {
		const { isSelected, setState, attributes, setAttributes } = props;

		const {
			cardHeader,
      cardContent,
      imgID,
      imgURL,
      imgAlt
		} = attributes;

		return [
			<div className={props.className}>
        <div className="card-wrapper">

          <div className="card-image">
            {!imgID ? (
              <div className="card-image-editor-wrapper">
                <MediaUpload
                  onSelect={img =>
                    setAttributes({
                      imgID: img.id,
                      imgURL: img.url,
                      imgAlt: img.alt
                    })
                  }
                  type="image"
                  value={imgID}
                  render={({ open }) => (
                    <Button
                      className="components-button button button-medium"
                      onClick={open}
                    >
                      {__('Add Image')}
                    </Button>
                  )}
                />
                <p>{__('Image dimensions: 260x200px.')}</p>
              </div>
            ) : (
              <div>
                <img
                  src={imgURL}
                  alt={imgAlt}
                />
                {isSelected ? (
                  <Button
                    className=""
                    onClick={() =>
                      setAttributes({
                        imgID: null,
                        imgURL: null,
                        imgAlt: null
                      })
                    }
                  >
                  </Button>
                ) : null}
              </div>
            )}
          </div>

          <div className="card-content-wrapper">
            <RichText
              tagName="h3"
              placeholder={__('Card Header')}
              className="card-header"
              value={cardHeader}
              onChange={value =>
                setAttributes({ cardHeader: value })
              }
              keepPlaceholderOnFocus={true}
              unstableOnFocus={() =>
                setState({ editable: '' })
              }
            />

            <RichText
              tagName="p"
              placeholder={__('Card content.')}
              className="card-content"
              value={cardContent}
              onChange={value =>
                setAttributes({ cardContent: value })
              }
              keepPlaceholderOnFocus={true}
              unstableOnFocus={() =>
                setState({ editable: 'body' })
              }
            />
          </div>

        </div>
      </div>
		];
	}),

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save: function(props) {
		const {
			cardHeader,
			cardContent,
      imgID,
      imgURL,
      imgAlt
		} = props.attributes;

		return (
			<div className={props.className} data-aos="fade-in">
        <div className="card-wrapper">
          { imgID ? (
            <div className="card-image">
              <img
                src={imgURL}
                alt={imgAlt}
              />
            </div>
          ) : null }
          <div className="card-content-wrapper">
            <h3 className="card-header">
              {cardHeader}
            </h3>
            <p className="card-content">
              {cardContent}
            </p>
          </div>
        </div>
			</div>
		);
	}
});
