import { Component, OnInit } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-edit-car',
  templateUrl: './edit-car.component.html',
  styleUrls: ['./edit-car.component.css'],
})
export class EditCarComponent implements OnInit {
  authToken: string = '';
  carData = {
    brand: '',
    model: '',
    licensePlate: '',
    dailyRent: 0,
    photos: [] as File[],
    availability: 'available',
  };
  carId: number;

  constructor(
    private http: HttpClient,
    private route: ActivatedRoute,
    private router: Router
  ) {
    this.carId = 0;
  }

  ngOnInit() {
    this.authToken = localStorage.getItem('authToken') || '';
    this.carId = +this.route.snapshot.paramMap.get('id')!;
    this.fetchCar();
  }

  fetchCar() {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${this.authToken}`,
    });

    console.log(this.carData.availability);

    this.http
      .get(`http://localhost:8000/api/cars/${this.carId}`, { headers })
      .subscribe(
        (response: any) => {
          this.carData = {
            brand: response.brand,
            model: response.model,
            licensePlate: response.license_plate,
            dailyRent: response.daily_rent,
            availability: response.availability,
            photos: response.photos ? JSON.parse(response.photos) : [], // Jika photos berupa JSON string
          };
        },
        (error) => {
          console.error('Failed to fetch car', error);
        }
      );
  }

  onFileChange(event: any) {
    this.carData.photos = event.target.files;
  }

  onUpdate() {
    // Validasi input sebelum melanjutkan
    if (
      !this.carData.brand ||
      !this.carData.model ||
      !this.carData.licensePlate ||
      !this.carData.dailyRent ||
      this.carData.photos.length === 0
    ) {
      alert('All fields must be filled, including at least one photo.');
      return;
    }

    const formData = new FormData();
    formData.append('brand', this.carData.brand);
    formData.append('model', this.carData.model);
    formData.append('licensePlate', this.carData.licensePlate);
    formData.append('dailyRent', this.carData.dailyRent.toString());
    formData.append('availability', this.carData.availability);

    for (let i = 0; i < this.carData.photos.length; i++) {
      formData.append('photos[]', this.carData.photos[i]);
    }

    const headers = new HttpHeaders({
      Authorization: `Bearer ${this.authToken}`,
    });

    this.http
      .post(`http://localhost:8000/api/cars/${this.carId}/update`, formData, {
        headers,
      })
      .subscribe(
        (response) => {
          console.log('Car updated successfully', response);
          this.router.navigate(['/cars']);
        },
        (error) => {
          console.error('Failed to update car', error);
          alert('Failed to update car. Please check your input.');
        }
      );
  }
}
