
import { __ } from '@wordpress/i18n';
import { useEffect } from '@wordpress/element';
import {
	useBlockProps,
	InspectorControls,
	RichText,
} from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	TextControl,
	TextareaControl,
} from '@wordpress/components';
import TypographyPanel, { typographyToStyle } from './typography-panel';
import './editor.scss';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const {
		formInstanceId,
		layoutType,
		heading,
		subheading,
		notificationEmail,
		successMessage,
		labelFirstName,
		labelLastName,
		labelEmail,
		labelPhone,
		labelMessage,
		placeholderFirstName,
		placeholderLastName,
		placeholderEmail,
		placeholderPhone,
		placeholderMessage,
		buttonText,
		headingTypography,
		subheadingTypography,
		labelFirstNameTypography,
		labelLastNameTypography,
		labelEmailTypography,
		labelPhoneTypography,
		labelMessageTypography,
		inputFirstNameTypography,
		inputLastNameTypography,
		inputEmailTypography,
		inputPhoneTypography,
		inputMessageTypography,
		buttonTypography,
	} = attributes;

	// Generate a stable form instance ID for server-side attribute resolution.
	useEffect( () => {
		if ( ! formInstanceId ) {
			setAttributes( { formInstanceId: clientId } );
		}
	}, [ formInstanceId, clientId, setAttributes ] );

	const blockProps = useBlockProps( {
		className: `kdm-lp-form-box kdm-layout-${ layoutType }`,
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Layout', 'telex-kdm-premium-form' ) }
					initialOpen={ true }
				>
					<SelectControl
						label={ __( 'Layout Type', 'telex-kdm-premium-form' ) }
						value={ layoutType }
						options={ [
							{
								label: __( 'Compact Box (Sidebar)', 'telex-kdm-premium-form' ),
								value: 'compact',
							},
							{
								label: __( 'Wide Row (Footer)', 'telex-kdm-premium-form' ),
								value: 'wide',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { layoutType: value } )
						}
					/>
				</PanelBody>
				<PanelBody
					title={ __( 'Form Settings', 'telex-kdm-premium-form' ) }
					initialOpen={ true }
				>
					<TextControl
						label={ __( 'Notification Email', 'telex-kdm-premium-form' ) }
						help={ __( 'Submissions will be sent to this email. Leave empty to use admin email.', 'telex-kdm-premium-form' ) }
						value={ notificationEmail }
						onChange={ ( value ) =>
							setAttributes( { notificationEmail: value } )
						}
						type="email"
					/>
					<TextareaControl
						label={ __( 'Success Message', 'telex-kdm-premium-form' ) }
						help={ __( 'Message shown after a successful submission.', 'telex-kdm-premium-form' ) }
						value={ successMessage }
						onChange={ ( value ) =>
							setAttributes( { successMessage: value } )
						}
					/>
				</PanelBody>
				<PanelBody
					title={ __( 'Field Placeholders', 'telex-kdm-premium-form' ) }
					initialOpen={ false }
				>
					<TextControl
						label={ __( 'First Name Placeholder', 'telex-kdm-premium-form' ) }
						value={ placeholderFirstName }
						onChange={ ( value ) =>
							setAttributes( { placeholderFirstName: value } )
						}
					/>
					<TextControl
						label={ __( 'Last Name Placeholder', 'telex-kdm-premium-form' ) }
						value={ placeholderLastName }
						onChange={ ( value ) =>
							setAttributes( { placeholderLastName: value } )
						}
					/>
					<TextControl
						label={ __( 'Email Placeholder', 'telex-kdm-premium-form' ) }
						value={ placeholderEmail }
						onChange={ ( value ) =>
							setAttributes( { placeholderEmail: value } )
						}
					/>
					<TextControl
						label={ __( 'Phone Placeholder', 'telex-kdm-premium-form' ) }
						value={ placeholderPhone }
						onChange={ ( value ) =>
							setAttributes( { placeholderPhone: value } )
						}
					/>
					<TextControl
						label={ __( 'Message Placeholder', 'telex-kdm-premium-form' ) }
						value={ placeholderMessage }
						onChange={ ( value ) =>
							setAttributes( { placeholderMessage: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<InspectorControls group="styles">
				<TypographyPanel
					label={ __( 'Heading Typography', 'telex-kdm-premium-form' ) }
					value={ headingTypography }
					onChange={ ( val ) => setAttributes( { headingTypography: val } ) }
				/>
				<TypographyPanel
					label={ __( 'Subheading Typography', 'telex-kdm-premium-form' ) }
					value={ subheadingTypography }
					onChange={ ( val ) => setAttributes( { subheadingTypography: val } ) }
				/>
				<TypographyPanel
					label={ __( 'First Name — Label', 'telex-kdm-premium-form' ) }
					value={ labelFirstNameTypography }
					onChange={ ( val ) => setAttributes( { labelFirstNameTypography: val } ) }
				/>
				<TypographyPanel
					label={ __( 'First Name — Input', 'telex-kdm-premium-form' ) }
					value={ inputFirstNameTypography }
					onChange={ ( val ) => setAttributes( { inputFirstNameTypography: val } ) }
				/>
				<TypographyPanel
					label={ __( 'Last Name — Label', 'telex-kdm-premium-form' ) }
					value={ labelLastNameTypography }
					onChange={ ( val ) => setAttributes( { labelLastNameTypography: val } ) }
				/>
				<TypographyPanel
					label={ __( 'Last Name — Input', 'telex-kdm-premium-form' ) }
					value={ inputLastNameTypography }
					onChange={ ( val ) => setAttributes( { inputLastNameTypography: val } ) }
				/>
				<TypographyPanel
					label={ __( 'Email — Label', 'telex-kdm-premium-form' ) }
					value={ labelEmailTypography }
					onChange={ ( val ) => setAttributes( { labelEmailTypography: val } ) }
				/>
				<TypographyPanel
					label={ __( 'Email — Input', 'telex-kdm-premium-form' ) }
					value={ inputEmailTypography }
					onChange={ ( val ) => setAttributes( { inputEmailTypography: val } ) }
				/>
				<TypographyPanel
					label={ __( 'Phone — Label', 'telex-kdm-premium-form' ) }
					value={ labelPhoneTypography }
					onChange={ ( val ) => setAttributes( { labelPhoneTypography: val } ) }
				/>
				<TypographyPanel
					label={ __( 'Phone — Input', 'telex-kdm-premium-form' ) }
					value={ inputPhoneTypography }
					onChange={ ( val ) => setAttributes( { inputPhoneTypography: val } ) }
				/>
				<TypographyPanel
					label={ __( 'Message — Label', 'telex-kdm-premium-form' ) }
					value={ labelMessageTypography }
					onChange={ ( val ) => setAttributes( { labelMessageTypography: val } ) }
				/>
				<TypographyPanel
					label={ __( 'Message — Input', 'telex-kdm-premium-form' ) }
					value={ inputMessageTypography }
					onChange={ ( val ) => setAttributes( { inputMessageTypography: val } ) }
				/>
				<TypographyPanel
					label={ __( 'Button Typography', 'telex-kdm-premium-form' ) }
					value={ buttonTypography }
					onChange={ ( val ) => setAttributes( { buttonTypography: val } ) }
				/>
			</InspectorControls>
			<div { ...blockProps }>
				<div className="kdm-form-header">
					<RichText
						tagName="h3"
						style={ typographyToStyle( headingTypography ) }
						value={ heading }
						onChange={ ( value ) =>
							setAttributes( { heading: value } )
						}
						placeholder={ __( 'Form Heading…', 'telex-kdm-premium-form' ) }
					/>
					<RichText
						tagName="p"
						className="kdm-form-subheading"
						style={ typographyToStyle( subheadingTypography ) }
						value={ subheading }
						onChange={ ( value ) =>
							setAttributes( { subheading: value } )
						}
						placeholder={ __( 'Subheading text…', 'telex-kdm-premium-form' ) }
					/>
				</div>
				<div className="kdm-form-preview">
					<div className="kdm-lp-form-row">
						<div className="kdm-lp-form-group">
							<RichText
								tagName="label"
								style={ typographyToStyle( labelFirstNameTypography ) }
								value={ labelFirstName }
								onChange={ ( value ) =>
									setAttributes( { labelFirstName: value } )
								}
								placeholder={ __( 'Label…', 'telex-kdm-premium-form' ) }
								allowedFormats={ [ 'core/bold', 'core/italic' ] }
							/>
							<input
								type="text"
								tabIndex="-1"
								readOnly
								placeholder={ placeholderFirstName }
								style={ typographyToStyle( inputFirstNameTypography ) }
							/>
						</div>
						<div className="kdm-lp-form-group">
							<RichText
								tagName="label"
								style={ typographyToStyle( labelLastNameTypography ) }
								value={ labelLastName }
								onChange={ ( value ) =>
									setAttributes( { labelLastName: value } )
								}
								placeholder={ __( 'Label…', 'telex-kdm-premium-form' ) }
								allowedFormats={ [ 'core/bold', 'core/italic' ] }
							/>
							<input
								type="text"
								tabIndex="-1"
								readOnly
								placeholder={ placeholderLastName }
								style={ typographyToStyle( inputLastNameTypography ) }
							/>
						</div>
					</div>
					<div className="kdm-lp-form-row">
						<div className="kdm-lp-form-group">
							<RichText
								tagName="label"
								style={ typographyToStyle( labelEmailTypography ) }
								value={ labelEmail }
								onChange={ ( value ) =>
									setAttributes( { labelEmail: value } )
								}
								placeholder={ __( 'Label…', 'telex-kdm-premium-form' ) }
								allowedFormats={ [ 'core/bold', 'core/italic' ] }
							/>
							<input
								type="email"
								tabIndex="-1"
								readOnly
								placeholder={ placeholderEmail }
								style={ typographyToStyle( inputEmailTypography ) }
							/>
						</div>
						<div className="kdm-lp-form-group">
							<RichText
								tagName="label"
								style={ typographyToStyle( labelPhoneTypography ) }
								value={ labelPhone }
								onChange={ ( value ) =>
									setAttributes( { labelPhone: value } )
								}
								placeholder={ __( 'Label…', 'telex-kdm-premium-form' ) }
								allowedFormats={ [ 'core/bold', 'core/italic' ] }
							/>
							<input
								type="tel"
								tabIndex="-1"
								readOnly
								placeholder={ placeholderPhone }
								style={ typographyToStyle( inputPhoneTypography ) }
							/>
						</div>
					</div>
					<div className="kdm-lp-form-group">
						<RichText
							tagName="label"
							style={ typographyToStyle( labelMessageTypography ) }
							value={ labelMessage }
							onChange={ ( value ) =>
								setAttributes( { labelMessage: value } )
							}
							placeholder={ __( 'Label…', 'telex-kdm-premium-form' ) }
							allowedFormats={ [ 'core/bold', 'core/italic' ] }
						/>
						<textarea
							tabIndex="-1"
							readOnly
							rows="3"
							placeholder={ placeholderMessage }
							style={ typographyToStyle( inputMessageTypography ) }
						></textarea>
					</div>
					<div className="kdm-turnstile-placeholder">
						<span>{ __( '🛡️ Turnstile widget appears here on the front end', 'telex-kdm-premium-form' ) }</span>
					</div>
					<RichText
						tagName="button"
						className="kdm-lp-form-submit"
						style={ typographyToStyle( buttonTypography ) }
						value={ buttonText }
						onChange={ ( value ) =>
							setAttributes( { buttonText: value } )
						}
						placeholder={ __( 'Button text…', 'telex-kdm-premium-form' ) }
						allowedFormats={ [ 'core/bold', 'core/italic' ] }
					/>
				</div>
			</div>
		</>
	);
}
