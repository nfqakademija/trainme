const validateDateInput = (eventStart, eventEnd, inputStart, inputEnd) => {
    return (inputStart >= eventStart && inputEnd <= eventEnd);
};

export default validateDateInput;