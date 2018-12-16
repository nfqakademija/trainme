import React from 'react';
import ReactDOM from 'react-dom';

import TrainerTags from './TrainerTags';

const tagsElement = document.getElementById('trainerTags');

ReactDOM.render(<TrainerTags allTags={JSON.parse(tagsElement.dataset.allTags)}
                             tags={JSON.parse(tagsElement.dataset.tags)}/>, tagsElement);