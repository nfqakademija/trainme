import React from 'react';
import ReactDOM from 'react-dom';

import TrainerCalendar from './TrainerCalendar';

const trainerCal = document.getElementById('trainerCal');

ReactDOM.render(<TrainerCalendar id={trainerCal.dataset.trainer}/>, trainerCal);

