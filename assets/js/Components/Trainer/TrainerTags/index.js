import React from 'react';
import ReactDOM from 'react-dom';

import TrainerTags from './TrainerTags';

const tags = document.getElementById('trainerTags');
const trainerTags = [
    {
        'id': '1',
        'text': 'Running'
    }, {
        'id': '2',
        'text': 'Yoga'
    }, {
        'id': '3',
        'text': 'Crossfit'
    }
];

const allTags = [
    {
        'id': '1',
        'text': 'Running'
    }, {
        'id': '2',
        'text': 'Yoga'
    }, {
        'id': '3',
        'text': 'Crossfit'
    }, {
        'id': '4',
        'text': 'Weight lifting'
    }
];

ReactDOM.render(<TrainerTags allTags={allTags} tags={trainerTags}/>, tags);