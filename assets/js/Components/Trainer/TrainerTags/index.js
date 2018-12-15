import React from 'react';
import ReactDOM from 'react-dom';

import TrainerTags from './TrainerTags';

const tags = document.getElementById('trainerTags');
const trainerTags = [
    {
        'id': '1',
        'name': 'Running'
    }, {
        'id': '2',
        'name': 'Yoga'
    }, {
        'id': '3',
        'name': 'Crossfit'
    }
];

const allTags = [
    {
        'id': '1',
        'name': 'Running'
    }, {
        'id': '2',
        'name': 'Yoga'
    }, {
        'id': '3',
        'name': 'Crossfit'
    }, {
        'id': '4',
        'name': 'Weight lifting'
    }
];

ReactDOM.render(<TrainerTags allTags={allTags} tags={trainerTags}/>, tags);