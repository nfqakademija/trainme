import React from 'react';
import 'select2';
import $ from 'jquery';
import axios from 'axios';

class TrainerTags extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            tags: this.props.tags,
            editing: false
        };
        this.selectBox = null;
    }

    editClicked() {
        const selectedTags = this.state.tags.map(tag => tag.id);
        this.setState({editing: true}, () => {
            this.selectBox = $('#select2Edit').select2({data: this.state.tags}).val(selectedTags).trigger('change');
        });
    }

    discardClicked() {
        this.setState({editing: false});
        this.selectBox.select2('destroy');
    }

    saveClicked() {
        const selectedTagIds = this.selectBox.select2('val');
        const selectedTags = this.props.allTags.filter(tag => selectedTagIds.includes(tag.id));
        axios.post('url', {
            'ids': selectedTagIds
        }).then(response => {
            this.setState({editing: false, tags: selectedTags}, () => {
                this.selectBox.select2('destroy');
            });
        }).catch(err => {
            this.setState({editing: false}, () => {
                this.selectBox.select2('destroy');
            });
            console.log(err);
        });
    }

    render() {
        const {
            tags,
            editing
        } = this.state;
        let tagList = null;
        let select = null;

        if (tags) {
            tagList = <ul className="filters__tags filters__tags--edit u-listNone">
                {tags.map(tag => (
                    <li key={tag.id}
                        className="filters__tag filters__tag--big filters__tag--noInteraction">{tag.text}</li>
                ))}
            </ul>
        }

        const editButtons = <div className="buttonContainer">
            {!editing ? <button className="btn"
                                onClick={() => this.editClicked()}>Edit
            </button> : <React.Fragment>
                <button className="btn btnSave btn--editingInfo"
                        onClick={() => this.saveClicked()}>Save
                </button>
                <button className="btn btn--cancel btnDiscard" onClick={() => this.discardClicked()}>Discard
                </button>
            </React.Fragment>}
        </div>;

        if (editing) {
            select = <select id="select2Edit" multiple="multiple">
                {this.props.allTags.map(tag => (
                    <option key={tag.id} value={tag.id}>{tag.text}</option>
                ))}
            </select>;
        }

        return (
            <React.Fragment>
                <p className="blackTitle">Tags</p>
                {tagList}
                {select}
                {editButtons}
            </React.Fragment>)
    }
}

export default TrainerTags;