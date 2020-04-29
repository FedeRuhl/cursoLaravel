let $doctor, $date, $specialty, $hours, $iRadio;
const noHoursAlert = `<div class="alert alert-danger" role="alert">
<strong> ¡Lo sentimos! </strong> No se han encontrado horarios disponibles para este médico en el día seleccionado.
                </div>`;

$(function () {
    const $specialty = $('#specialty'); //creamos la variable con $ para indicar que es de jquery
    $doctor = $('#doctor');
    $date = $('#date');
    $hours = $('#hours');

    $specialty.change(() =>{ //cuando el elemento de ID specialty cambie, ejecutaremos esta función
    const specialtyId = $specialty.val();
    const url = `/specialties/${specialtyId}/doctors`; //usamos este tipo de comillas para hacer la interpolación
    $.getJSON(url, onDoctorsLoaded); //funcion de callback
    });

    $date.change(() =>{
        loadHours();
    });

    $doctor.change(() =>{
        loadHours();
    });

});

function onDoctorsLoaded(doctors){
    let htmlOptions = '';
    doctors.forEach(function (doctor){
        htmlOptions += `<option value="${doctor.id}"> ${doctor.name} </option>`; 
    });
    $doctor.html(htmlOptions);
}

function loadHours(){
    const selectedDate = $date.val();
    const selectedDoctor = $doctor.val();
    const url = `/schedule/hours?date=${selectedDate}&doctor_id=${selectedDoctor}`;
    $.getJSON(url, displayHours);
}

function displayHours(data){
    let htmlHours = '';
    $iRadio = 0;
    if(data.length == 0){
        $hours.html(noHoursAlert);
        return;
    }

    if(data.morning){
        const morning_intervals = data.morning;
        morning_intervals.forEach(interval =>{
            htmlHours += getRadioHtml(interval);
        });
    }

    if(data.afternoon){
        const afternoon_intervals = data.afternoon;
        afternoon_intervals.forEach(interval =>{
            htmlHours += getRadioHtml(interval);
        });
    }

    $hours.html(htmlHours);
}

function getRadioHtml(interval){
    const text = `${interval.start} - ${interval.end}`;
    return `
<div class="custom-control custom-radio mb-3">
  <input name="scheduled_time" class="custom-control-input" id="interval${$iRadio}" type="radio" value="${interval.start}" required>
  <label class="custom-control-label" for="interval${$iRadio++}">${text}</label>
</div>
`;
}