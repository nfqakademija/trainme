import React from 'react';
import ReactDOM from 'react-dom';

import TrainerWorkoutsCalendar from './TrainerWorkoutsCalendar';

const trainerWorkouts = document.getElementById('trainerWorkouts');

ReactDOM.render(<TrainerWorkoutsCalendar id={trainerWorkouts.dataset.id}/>, trainerWorkouts);

