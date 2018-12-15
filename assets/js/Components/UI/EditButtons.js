import React from 'react';

const EditButtons = props => (
    <div className="buttonContainer">
        {!props.isEditing ? <button className="btn"
                                    onClick={props.onEdit}>Edit
        </button> : <React.Fragment>
            <button className="btn btnSave btn--editingInfo"
                    onClick={props.onSave}>{props.isSaving ? 'Saving...' : 'Save'}
            </button>
            <button className="btn btn--cancel btnDiscard" onClick={props.onDiscard}>Discard
            </button>
        </React.Fragment>}
    </div>
);

export default EditButtons;