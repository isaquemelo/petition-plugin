import { __ } from '@wordpress/i18n';
import { SelectControl, CheckboxControl, TextControl } from '@wordpress/components';
import { withState, useState } from '@wordpress/compose';
import '../assets/css/block-style.scss';

const { Component } = wp.element;
const { withSelect, select } = wp.data;


class FirstBlockEdit extends Component {

    render() {

        let choices = [];
        const { attributes, setAttributes } = this.props;

        if (this.props.posts) {
            choices.push({ value: 0, label: __('Select a petition', 'petition') });
            this.props.posts.forEach(post => {
                choices.push({ value: post.id, label: post.title.rendered });
            });
        } else {
            choices.push({ value: 0, label: __('Loading...', 'petition') })
        }

      
        return (
            <div> 
                <SelectControl
                    label={__('Petition')}
                    options={choices}
                    value={ attributes.postId }
                    onChange={ (value) => {setAttributes( { petitionID: value } ) } } 
                    class="d-block mb-15 post-select"
                />
                <TextControl
                    label={ __( 'Visible signature number' ) }
                    type="number"
                    value={ attributes.signaturesNumber }
                    onChange={ (value) => {setAttributes( { signaturesNumber: value } ) } }
                    class="d-block mb-15 signatures-number"
                  />
                <CheckboxControl
                    label={ __( 'Show signature numbers' ) }
                    checked={ attributes.showSignaturesNumber }
                    onChange={ (value) => {setAttributes( { showSignaturesNumber: value } ) } }
                />
                <CheckboxControl
                    label={ __( 'Show goal' ) }
                    checked={ attributes.showGoal }
                    onChange={ (value) => {setAttributes( { showGoal: value } ) } }
                />
            </div>
        )
    }
}



wp.blocks.registerBlockType('petitions/petition-block', {
	title: 'Petiton block',
	category: 'common',
	icon: 'smiley',
	description: 'Insert a petition block anywhere',
	keywords: ['petition', 'petitions'],
    attributes: {
        petitionID:{
            type: 'number',
            default: null
        },
        signaturesNumber:{
            type: 'number',
            default: 5
        },
        showSignaturesNumber:{
            type: 'boolean',
            default: true
        },
        showGoal:{
            type: 'boolean',
            default: true
        },
    },

	edit: withSelect(select => {
            return {
                posts: select('core').getEntityRecords('postType', 'petition')
            }
        })(FirstBlockEdit),
	
    save: (props) => { return null }
});

