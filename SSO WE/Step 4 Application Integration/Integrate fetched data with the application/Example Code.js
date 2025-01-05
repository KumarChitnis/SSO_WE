import React, { useState, useEffect } from 'react';
import ApiService from './ApiService';
import LoadingSpinner from './LoadingSpinner';
import ErrorMessage from './ErrorMessage';

const App = () => {
  const [userLocation, setUserLocation] = useState(null);
  const [nearbyRestaurants, setNearbyRestaurants] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        setError(null);
        
        // Fetch user location
        const locationData = await ApiService.getLocationData();
        setUserLocation(locationData);

        // Fetch nearby restaurants
        if (locationData) {
          const restaurants = await ApiService.getNearbyRestaurants(
            locationData.latitude,
            locationData.longitude
          );
          setNearbyRestaurants(restaurants);
        }
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  if (loading) {
    return <LoadingSpinner />;
  }

  if (error) {
    return <ErrorMessage message={error} />;
  }

  return (
    <div className="restaurant-list">
      <h2>Nearby Restaurants</h2>
      {nearbyRestaurants.length > 0 ? (
        <ul>
          {nearbyRestaurants.map((restaurant) => (
            <li key={restaurant.id} className="restaurant-item">
              <h3>{restaurant.name}</h3>
              <p>{restaurant.address}</p>
              <p>Rating: {restaurant.rating}</p>
            </li>
          ))}
        </ul>
      ) : (
        <p>No nearby restaurants found.</p>
      )}
    </div>
  );
};

export default App;
