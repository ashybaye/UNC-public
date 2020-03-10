/**
 * BLOCK: stat-box
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

const { RichText } = wp.blockEditor || wp.editor;

const { withState } = wp.compose;

const attributes = {
	columnNumber: {
		type: 'array',
		source: 'children',
		selector: '.stat-number'
	},
	columnBody: {
		type: 'array',
		source: 'children',
		selector: '.stat-body'
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
registerBlockType('unc/stat-block', {
	title: __('Stat'),
  icon: 'awards',
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
		const { isSelected, setAttributes, editable, setState } = props;

		const {
			columnNumber,
			columnBody
		} = props.attributes;

		return [
			<div className={props.className}>
        <div className="stat-wrapper">
          <div className="stat-number-wrapper">
            <RichText
              tagName="p"
              placeholder={__('100')}
              className="stat-number"
              value={columnNumber}
              onChange={value =>
                setAttributes({
                  columnNumber: value
                })
              }
              keepPlaceholderOnFocus={true}
              unstableOnFocus={() =>
                setState({ editable: '' })
              }
            />
          </div>
          <RichText
            tagName="p"
            placeholder={__('Additional info.')}
            className="stat-body"
            value={columnBody}
            onChange={value =>
              setAttributes({ columnBody: value })
            }
            keepPlaceholderOnFocus={true}
            unstableOnFocus={() =>
              setState({ editable: 'body' })
            }
          />
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
			columnNumber,
			columnTitle,
			columnBody
		} = props.attributes;

		return (
			<div className={props.className}>
        <div className="stat-wrapper">
          <div className="stat-number-wrapper">
            <p className="stat-number">
              {columnNumber}
            </p>
          </div>
          <p className="stat-body">
            {columnBody}
          </p>
        </div>
			</div>
		);
	}
});
