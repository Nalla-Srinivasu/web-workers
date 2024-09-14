onmessage = function(event) {
    const data = event.data;    
    const processedData = {
        firstname: data.firstname.toUpperCase(),
        middlename: data.middlename.toUpperCase(),
        lastname: data.lastname.toUpperCase()
    };    
    postMessage(JSON.stringify(processedData));
};