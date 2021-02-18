import { __ } from '@wordpress/i18n';
import { SelectControl, CheckboxControl, TextControl } from '@wordpress/components';
import { withState, useState } from '@wordpress/compose';

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
        }else{
            choices.push({ value: 0, label: __('Loading...', 'petition') })
        }
      
        return (
            <div class="wraper"> 
                <SelectControl
                    label={__('Petition')}
                    options={choices}
                    value={ attributes.petitionID }
                    onChange={ (petitionID) => {setAttributes( { petitionID: parseInt(petitionID) } ) } } 
                />
                <TextControl
                    label={ __( 'Visible signature number' ) }
                    type="number"
                    value={ attributes.showSignaturesMax }
                    onChange={ (showSignaturesMax) => {setAttributes( { showSignaturesMax: parseInt(showSignaturesMax) } ) } }
                  />
                <CheckboxControl
                    label={ __( 'Show signature numbers' ) }
                    checked={ attributes.showTotal }
                    onChange={ (showTotal) => {setAttributes( { showTotal: showTotal } ) } }
                />
                <CheckboxControl
                    label={ __( 'Show goal' ) }
                    checked={ attributes.showGoal }
                    onChange={ (showGoal) => {setAttributes( { showGoal: showGoal } ) } }
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
        showSignaturesMax:{
            type: 'number',
            default: 5
        },
        showTotal:{
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

