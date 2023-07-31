function formatDateTime(dateTimeString) {
    const year = dateTimeString.substr(0, 4);
    const month = dateTimeString.substr(4, 2);
    const day = dateTimeString.substr(6, 2);
    const hour = dateTimeString.substr(8, 2);
    const minute = dateTimeString.substr(10, 2);
    const second = dateTimeString.substr(12, 2);

    return `${year}-${month}-${day} ${hour}:${minute}:${second}`;
}