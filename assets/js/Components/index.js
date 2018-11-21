import React from 'react';
import ReactDOM from 'react-dom';
import Calendar from './Calendar';
import axios from 'axios';

const calendar = document.getElementById('calendar');
const trainerId =  calendar.dataset.trainer;

let events = [];

axios.all([
    axios.get(`/api/trainers/${trainerId}/scheduledWorkouts`),
    axios.get(`/api/trainers/${trainerId}/availabilitySlots`)
])
    .then(axios.spread((scheduledEventsRes, availabilitySlotsRes) => {
        scheduledEventsRes.data.forEach((item) => {
            events.push({
                'title': 'WORKOUT',
                'start': new Date(item.start),
                'end': new Date(item.end),
            })
        });

        availabilitySlotsRes.data.forEach((item) => {
            events.push({
                'title': 'AVAILABLE',
                'start': new Date(item.start),
                'end': new Date(item.end),
            })
        });

        console.log(events);
        ReactDOM.render(<Calendar events={events}/>, document.getElementById('calendar'));
    }))
    .catch((error) => {
        console.log(error)
    })

