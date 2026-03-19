import { useQuery } from '@tanstack/react-query';
import { Spin } from 'antd';
import { GoogleMap, useJsApiLoader, HeatmapLayer } from '@react-google-maps/api';
import { getHeatmapData } from '../api';
import { useMemo } from 'react';

const MAP_CENTER = { lat: -7.115, lng: 112.417 };
const LIBRARIES = ['visualization'];

export default function MapView() {
  const { isLoaded } = useJsApiLoader({
    googleMapsApiKey: 'AIzaSyDttz2AVabNjp4YkwrKs_cIsPzsq4zicFE',
    libraries: LIBRARIES,
  });

  const { data, isLoading } = useQuery({
    queryKey: ['heatmap'],
    queryFn: () => getHeatmapData().then((r) => r.data.data),
  });

  const heatmapData = useMemo(() => {
    if (!data || !window.google) return [];
    return data.map((r) => ({
      location: new window.google.maps.LatLng(parseFloat(r.latitude), parseFloat(r.longitude)),
      weight: parseFloat(r.priority_score) / 10,
    }));
  }, [data]);

  if (!isLoaded || isLoading) {
    return <Spin size="large" style={{ display: 'block', margin: '100px auto' }} />;
  }

  return (
    <div>
      <div className="page-header">
        <h1>Peta Kerusakan Jalan</h1>
      </div>

      <div style={{ borderRadius: 12, overflow: 'hidden', boxShadow: '0 1px 3px rgba(0,0,0,0.08)' }}>
        <GoogleMap
          mapContainerStyle={{ width: '100%', height: 'calc(100vh - 140px)' }}
          center={MAP_CENTER}
          zoom={13}
          options={{
            styles: [
              { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] },
            ],
          }}
        >
          {heatmapData.length > 0 && (
            <HeatmapLayer
              data={heatmapData}
              options={{
                radius: 30,
                opacity: 0.7,
                gradient: [
                  'rgba(0, 255, 0, 0)',
                  'rgba(0, 255, 0, 1)',
                  'rgba(255, 255, 0, 1)',
                  'rgba(255, 165, 0, 1)',
                  'rgba(255, 0, 0, 1)',
                ],
              }}
            />
          )}
        </GoogleMap>
      </div>

      <div style={{ display: 'flex', gap: 16, marginTop: 12, justifyContent: 'center' }}>
        {[
          { color: '#2A9D8F', label: 'Ringan' },
          { color: '#F4A261', label: 'Sedang' },
          { color: '#E63946', label: 'Berat' },
        ].map((l) => (
          <div key={l.label} style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
            <div style={{ width: 12, height: 12, borderRadius: '50%', background: l.color }} />
            <span style={{ fontSize: 13, color: '#6C757D' }}>{l.label}</span>
          </div>
        ))}
      </div>
    </div>
  );
}
