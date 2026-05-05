<div>
    <div class="container mx-auto max-w-4xl mt-5">
        <div class="bg-white p-6 rounded-lg shadow-lg ">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 ">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Informasi Pegawai</h2>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p><strong>Nama Pegawai: </strong> {{ $schedule->user->name }}</p>
                        <p><strong>Kantor: </strong> {{ $schedule->office->name }}</p>
                        <p><strong>Shift: </strong> {{ $schedule->shift->name }}</p>
                        @if ($schedule->is_wfa)
                            <p class="text-green-600"><strong>Status:</strong>WFA</p>
                        @else
                            <p class="text-red-400"><strong>Status:</strong>WFO</p>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h4 class="text-lg font-bold mb-2">Jadwal Masuk </h4>
                        <p><strong>{{ $attendance->start_time ?? 'Belum Absen' }}</strong></p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h4 class="text-lg font-bold mb-2">Jadwal Keluar</h4>
                        <p><strong>{{ $attendance->end_time ?? 'Belum Absen' }}</strong></p>
                    </div>
                </div>
                </div>
 
                <div>
                    <h2 class="text-2xl font-bold mb-2">Presensi</h2>
                    <div id="map" class="my-2 rounded" wire:ignore></div>
                    <form class="flex justify-between" method="POST" wire:submit='store'>
                        <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded mb-1 hover:cursor-pointer hover:bg-blue-700 transition" onclick="tagLocation()">Tag Location</button>
                        @if ($insideRadius)
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded mb-1 hover:cursor-pointer hover:bg-green-700 transition">Submit Presensi</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let marker;
    let office = [{{ $schedule->office->latitude }}, {{ $schedule->office->longitude }}];
    let radius = {{ $schedule->office->radius }};
    let map;
    let lat;
    let long;
    let component;
    const isWFA = @json($schedule->is_wfa);

    document.addEventListener('livewire:initialized', function (){
        component = @this;

        map = L.map('map').setView(office, 17);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        
        }).addTo(map);

        var circle = L.circle(office, {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: radius
        }).addTo(map);
    });


    

    function tagLocation(){
        if (navigator.geolocation){
            navigator.geolocation.getCurrentPosition(function(position){
                Lat = position.coords.latitude;
                Long = position.coords.longitude;
                
                if(marker){
                    map.removeLayer(marker);
                }

                marker = L.marker([ Lat, Long]).addTo(map);
                map.setView([ Lat, Long], 17);

                if(isWithinRadius(Lat, Long, office, radius)){
                    component.set('insideRadius', true);
                    component.set('latitude', Lat);
                    component.set('longitude', Long);
                } else {
                    component.set('insideRadius', false);
                    if(isWFA){
                        component.set('insideRadius', true);
                        component.set('latitude', Lat);
                        component.set('longitude', Long);
                    } else {
                        alert("Anda berada di luar radius kantor, pastikan anda berada di kantor untuk melakukan presensi");
                    }
                    
                }
                
                
            })
        } else{
            alert("Geolocation is not supported by this browser.");
        }
    }

    function isWithinRadius(lat,long, center, radius){
        let distance = map.distance([lat, long], center);
        return distance <= radius;
    }
    
</script>
