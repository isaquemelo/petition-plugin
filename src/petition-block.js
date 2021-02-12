import { __ } from '@wordpress/i18n'
import { SelectControl } from '@wordpress/components';
import { withState } from '@wordpress/compose';

registerBlockType('petitions/petition-block', {
	title: 'Petiton block',
	category: 'common',
	icon: 'smiley',
	description: 'Insert a petition block anywhere',
	keywords: ['petition', 'petitions'],
	edit: () => { 
		return <div>
                    <SelectControl
                        multiple
                        label={ __( 'Select some users:' ) }
                        value={ this.state.users } // e.g: value = [ 'a', 'c' ]
                        onChange={ ( users ) => { this.setState( { users } ) } }
                        options={ [
                            { value: null, label: 'Select a User', disabled: true },
                            { value: 'a', label: 'User A' },
                            { value: 'b', label: 'User B' },
                            { value: 'c', label: 'User c' },
                        ] }
                    />
                </div>
	},
	save: () => { 
		return 
        <div>
           <p>conte</p>   
        </div> 
	}
});