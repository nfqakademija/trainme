import React from 'react';
import ReactDOM from 'react-dom';

import TrainerInfo from './TrainerInfo';

const trainerInfo = document.getElementById('trainerInfo');

ReactDOM.render(<TrainerInfo trainer={trainerInfo.dataset.trainer} count={trainerInfo.dataset.count}
                             user={trainerInfo.dataset.user}/>, trainerInfo);