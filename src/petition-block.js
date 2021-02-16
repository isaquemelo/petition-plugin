import { __ } from '@wordpress/i18n'
import { SelectControl } from '@wordpress/components';
import { withState } from '@wordpress/compose';

const { Component } = wp.element;
const { withSelect, select } = wp.data;

class FirstBlockEdit extends Component {

    render() {
        let choices = [];
        if (this.props.posts) {
            choices.push({ value: 0, label: __('Select a post', 'petition') });
            this.props.posts.forEach(post => {
                choices.push({ value: post.id, label: post.title.rendered });
            });
        } else {
            choices.push({ value: 0, label: __('Loading...', 'petition') })
        }

        return (<SelectControl
        label={__('Petition', 'petition')}
        options={choices}
        value={this.props.attributes.selectedPostId}
        onChange={(newval) => setAttributes({ selectedPostId: parseInt(newval) })}
        />);
    }
}

wp.blocks.registerBlockType('petitions/petition-block', {
	title: 'Petiton block',
	category: 'common',
	icon: 'smiley',
	description: 'Insert a petition block anywhere',
	keywords: ['petition', 'petitions'],
    attributes: {
        selectedPostId:{
            type: 'interger',
        },
    },
	edit: withSelect(select => {
            return {
                posts: select('core').getEntityRecords('postType', 'petition')
            }
        })(FirstBlockEdit),
	
    save: (props) => { 
		return <div>:)</div> 
	},
});