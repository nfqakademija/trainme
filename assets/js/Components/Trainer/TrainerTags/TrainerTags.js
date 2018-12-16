import React from 'react';
import 'select2';
import $ from 'jquery';
import axios from 'axios';

import EditButtons from "../../UI/EditButtons";

class TrainerTags extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            tags: this.props.tags,
            isEditing: false,
            isSaving: false
        };
        this.selectBox = null;
    }

    editClicked() {
        const selectedTags = this.state.tags.map(tag => tag.id);
        this.setState({isEditing: true}, () => {
            this.selectBox = $('#select2Edit').select2({data: this.state.tags}).val(selectedTags).trigger('change');
        });
    }

    discardClicked() {
        this.setState({isEditing: false});
        this.selectBox.select2('destroy');
    }

    saveClicked() {
        const selectedTagIds = this.selectBox.select2('val');
        const intIds = selectedTagIds.map(id => parseInt(id));
        const selectedTags = this.props.allTags.filter(tag => selectedTagIds.includes(tag.id));
        this.setState({isSaving: true});
        axios.put('/api/trainer', {
            'tags': intIds
        }).then(response => {
            this.setState({
                isEditing: false,
                tags: JSON.parse(response.data.tags),
                isSaving: false
            }, () => {
                this.selectBox.select2('destroy');
            });
        }).catch(err => {
            this.setState({
                isEditing: false,
                isSaving: false
            }, () => {
                this.selectBox.select2('destroy');
            });
            console.log(err);
        });
    }

    render() {
        const {
            tags,
            isEditing,
            isSaving
        } = this.state;
        let tagList = null;
        let select = null;

        if (tags) {
            tagList = <ul className="filters__tags filters__tags--edit u-listNone">
                {tags.map(tag => (
                    <li key={tag.id}
                        className="filters__tag filters__tag--big filters__tag--noInteraction">{tag.name}</li>
                ))}
            </ul>
        }

        if (isEditing) {
            select = <select id="select2Edit" multiple="multiple">
                {this.props.allTags.map(tag => (
                    <option key={tag.id} value={tag.id}>{tag.name}</option>
                ))}
            </select>;
        }

        return (
            <React.Fragment>
                <p className="blackTitle">Tags</p>
                {tagList}
                {select}
                <EditButtons onEdit={() => this.editClicked()}
                             isEditing={isEditing}
                             isSaving={isSaving}
                             onSave={() => this.saveClicked()}
                             onDiscard={() => this.discardClicked()}/>
            </React.Fragment>)
    }
}

export default TrainerTags;