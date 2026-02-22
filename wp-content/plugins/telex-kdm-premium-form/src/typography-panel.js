
import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import {
	PanelBody,
	SelectControl,
	ComboboxControl,
	__experimentalUnitControl as UnitControl,
	FlexBlock,
	Flex,
} from '@wordpress/components';

const FONT_WEIGHT_OPTIONS = [
	{ label: __( 'Default', 'telex-kdm-premium-form' ), value: '' },
	{ label: '100 — Thin', value: '100' },
	{ label: '200 — Extra Light', value: '200' },
	{ label: '300 — Light', value: '300' },
	{ label: '400 — Normal', value: '400' },
	{ label: '500 — Medium', value: '500' },
	{ label: '600 — Semi Bold', value: '600' },
	{ label: '700 — Bold', value: '700' },
	{ label: '800 — Extra Bold', value: '800' },
	{ label: '900 — Black', value: '900' },
];

const TEXT_TRANSFORM_OPTIONS = [
	{ label: __( 'Default', 'telex-kdm-premium-form' ), value: '' },
	{ label: __( 'None', 'telex-kdm-premium-form' ), value: 'none' },
	{ label: __( 'Uppercase', 'telex-kdm-premium-form' ), value: 'uppercase' },
	{ label: __( 'Lowercase', 'telex-kdm-premium-form' ), value: 'lowercase' },
	{ label: __( 'Capitalize', 'telex-kdm-premium-form' ), value: 'capitalize' },
];

const TEXT_DECORATION_OPTIONS = [
	{ label: __( 'Default', 'telex-kdm-premium-form' ), value: '' },
	{ label: __( 'None', 'telex-kdm-premium-form' ), value: 'none' },
	{ label: __( 'Underline', 'telex-kdm-premium-form' ), value: 'underline' },
	{ label: __( 'Line Through', 'telex-kdm-premium-form' ), value: 'line-through' },
];

const TEXT_STYLE_OPTIONS = [
	{ label: __( 'Default', 'telex-kdm-premium-form' ), value: '' },
	{ label: __( 'Normal', 'telex-kdm-premium-form' ), value: 'normal' },
	{ label: __( 'Italic', 'telex-kdm-premium-form' ), value: 'italic' },
];

/**
 * System / web-safe font stack fallbacks offered when no theme fonts are found.
 */
const SYSTEM_FONT_OPTIONS = [
	{ value: '', label: __( 'Default', 'telex-kdm-premium-form' ) },
	{ value: 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif', label: 'System UI' },
	{ value: 'serif', label: 'Serif' },
	{ value: 'sans-serif', label: 'Sans-serif' },
	{ value: 'monospace', label: 'Monospace' },
	{ value: 'cursive', label: 'Cursive' },
];

/**
 * Hook that retrieves every font family registered in the current WordPress
 * environment via Global Styles (theme.json typography.fontFamilies) across
 * default, theme, and custom origins.
 */
function useAvailableFonts() {
	return useSelect( ( select ) => {
		/*
		 * The block-editor store exposes settings that include all merged
		 * typography font families from theme.json (default + theme + custom).
		 */
		const settings = select( 'core/block-editor' ).getSettings();

		/*
		 * __experimentalFeatures holds the fully merged theme.json data
		 * including fonts installed through the Font Library UI.
		 */
		const features = settings?.__experimentalFeatures;
		const fontFamilies = features?.typography?.fontFamilies;

		if ( ! fontFamilies ) {
			return [];
		}

		const collected = [];

		/*
		 * fontFamilies is keyed by origin: "default", "theme", "custom".
		 * Each origin contains an array of { fontFamily, name, slug, ... }.
		 */
		const origins = [ 'default', 'theme', 'custom' ];
		origins.forEach( ( origin ) => {
			const list = fontFamilies[ origin ];
			if ( Array.isArray( list ) ) {
				list.forEach( ( entry ) => {
					if ( entry.fontFamily ) {
						collected.push( {
							value: entry.fontFamily,
							label: entry.name || entry.fontFamily,
						} );
					}
				} );
			}
		} );

		return collected;
	}, [] );
}

export default function TypographyPanel( { label, value = {}, onChange, initialOpen = false } ) {
	const themeFonts = useAvailableFonts();

	const fontOptions = themeFonts.length > 0
		? [ { value: '', label: __( 'Default', 'telex-kdm-premium-form' ) }, ...themeFonts ]
		: SYSTEM_FONT_OPTIONS;

	const update = ( key, val ) => {
		onChange( { ...value, [ key ]: val } );
	};

	return (
		<PanelBody title={ label } initialOpen={ initialOpen }>
			<div style={ { marginBottom: '12px' } }>
				<ComboboxControl
					label={ __( 'Font Family', 'telex-kdm-premium-form' ) }
					value={ value.fontFamily || '' }
					options={ fontOptions }
					onChange={ ( val ) => update( 'fontFamily', val || '' ) }
					allowReset={ true }
				/>
			</div>
			<Flex wrap={ true } gap={ 3 } style={ { marginBottom: '12px' } }>
				<FlexBlock style={ { minWidth: '120px' } }>
					<UnitControl
						label={ __( 'Font Size', 'telex-kdm-premium-form' ) }
						value={ value.fontSize || '' }
						onChange={ ( val ) => update( 'fontSize', val ) }
						units={ [
							{ value: 'px', label: 'px' },
							{ value: 'em', label: 'em' },
							{ value: 'rem', label: 'rem' },
						] }
					/>
				</FlexBlock>
				<FlexBlock style={ { minWidth: '120px' } }>
					<UnitControl
						label={ __( 'Line Height', 'telex-kdm-premium-form' ) }
						value={ value.lineHeight || '' }
						onChange={ ( val ) => update( 'lineHeight', val ) }
						units={ [
							{ value: '', label: '\u2014' },
							{ value: 'px', label: 'px' },
							{ value: 'em', label: 'em' },
						] }
					/>
				</FlexBlock>
			</Flex>
			<Flex wrap={ true } gap={ 3 } style={ { marginBottom: '12px' } }>
				<FlexBlock style={ { minWidth: '120px' } }>
					<UnitControl
						label={ __( 'Letter Spacing', 'telex-kdm-premium-form' ) }
						value={ value.letterSpacing || '' }
						onChange={ ( val ) => update( 'letterSpacing', val ) }
						units={ [
							{ value: 'px', label: 'px' },
							{ value: 'em', label: 'em' },
						] }
					/>
				</FlexBlock>
				<FlexBlock style={ { minWidth: '120px' } }>
					<SelectControl
						label={ __( 'Font Weight', 'telex-kdm-premium-form' ) }
						value={ value.fontWeight || '' }
						options={ FONT_WEIGHT_OPTIONS }
						onChange={ ( val ) => update( 'fontWeight', val ) }
					/>
				</FlexBlock>
			</Flex>
			<Flex wrap={ true } gap={ 3 } style={ { marginBottom: '12px' } }>
				<FlexBlock style={ { minWidth: '120px' } }>
					<SelectControl
						label={ __( 'Text Transform', 'telex-kdm-premium-form' ) }
						value={ value.textTransform || '' }
						options={ TEXT_TRANSFORM_OPTIONS }
						onChange={ ( val ) => update( 'textTransform', val ) }
					/>
				</FlexBlock>
				<FlexBlock style={ { minWidth: '120px' } }>
					<SelectControl
						label={ __( 'Text Decoration', 'telex-kdm-premium-form' ) }
						value={ value.textDecoration || '' }
						options={ TEXT_DECORATION_OPTIONS }
						onChange={ ( val ) => update( 'textDecoration', val ) }
					/>
				</FlexBlock>
			</Flex>
			<SelectControl
				label={ __( 'Font Style', 'telex-kdm-premium-form' ) }
				value={ value.fontStyle || '' }
				options={ TEXT_STYLE_OPTIONS }
				onChange={ ( val ) => update( 'fontStyle', val ) }
			/>
		</PanelBody>
	);
}

export function typographyToStyle( typo = {} ) {
	const style = {};
	if ( typo.fontFamily ) style.fontFamily = typo.fontFamily;
	if ( typo.fontSize ) style.fontSize = typo.fontSize;
	if ( typo.fontWeight ) style.fontWeight = typo.fontWeight;
	if ( typo.fontStyle ) style.fontStyle = typo.fontStyle;
	if ( typo.lineHeight ) style.lineHeight = typo.lineHeight;
	if ( typo.letterSpacing ) style.letterSpacing = typo.letterSpacing;
	if ( typo.textTransform ) style.textTransform = typo.textTransform;
	if ( typo.textDecoration ) style.textDecoration = typo.textDecoration;
	return style;
}
