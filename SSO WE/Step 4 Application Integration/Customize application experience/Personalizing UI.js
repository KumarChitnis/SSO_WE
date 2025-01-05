const userData = await fetchUserData();

if (userData) {
  const userName = userData.name;
  const userProfilePicture = userData.profile_picture;

  // Update UI with user's name and profile picture
  document.getElementById('user-name').textContent = userName;
  document.getElementById('user-profile-picture').src = userProfilePicture;
}