import moment from 'moment';

const validateSlot = (slots, date, from, to) => {
    const inputFrom = new Date(`${moment(date).format('YYYY-MM-DD')} ${moment(from).format('HH:mm:ss')}`).getTime();
    const inputTo = new Date(`${moment(date).format('YYYY-MM-DD')} ${moment(to).format('HH:mm:ss')}`).getTime();
    let result = true;

    slots.map(slot => {
        const slotFrom = new Date(slot.starts_at).getTime();
        const slotTo = new Date(slot.ends_at).getTime();

        if (!(inputTo <= slotFrom || inputFrom >= slotTo)) {
            result = false;
        }
    });

    return result;
};

export default validateSlot;