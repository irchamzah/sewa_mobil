import { Component, OnInit } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Router } from '@angular/router';

interface Car {
  id: number;
  brand: string;
  model: string;
  license_plate: string;
  daily_rent: number;
  availability: string;
  photos: string[];
}

@Component({
  selector: 'app-car-management',
  templateUrl: './car-management.component.html',
  styleUrls: ['./car-management.component.css'],
})
export class CarManagementComponent implements OnInit {
  authToken: string = '';
  carData = {
    brand: '',
    model: '',
    licensePlate: '',
    dailyRent: 0,
    photos: [] as File[],
    availability: 'available',
  };
  cars: Car[] = [];
  searchQuery: string = '';

  filters = {
    brand: '',
    model: '',
    availability: '',
  };

  filteredCars: Car[] = [];
  currentPage = 1;
  totalPages = 1;

  constructor(private http: HttpClient, private router: Router) {}

  ngOnInit() {
    this.authToken = localStorage.getItem('authToken') || '';
    if (this.authToken) {
      this.fetchCars();
    } else {
      console.error('No auth token found, redirecting to login...');
      this.router.navigate(['/login']);
    }
  }

  onFileChange(event: any) {
    this.carData.photos = event.target.files;
  }

  addCar() {
    // Validasi form data
    if (
      !this.carData.brand ||
      !this.carData.model ||
      !this.carData.licensePlate ||
      !this.carData.dailyRent
    ) {
      alert('Please fill out all required fields.');
      return;
    }

    // Periksa apakah ada file foto yang dipilih
    if (!this.carData.photos || this.carData.photos.length === 0) {
      alert('Please upload at least one photo.');
      return;
    }

    // Membuat FormData
    const formData = new FormData();
    formData.append('brand', this.carData.brand);
    formData.append('model', this.carData.model);
    formData.append('licensePlate', this.carData.licensePlate);
    formData.append('dailyRent', this.carData.dailyRent.toString());
    formData.append('availability', this.carData.availability);

    // Menambahkan foto ke FormData
    for (let i = 0; i < this.carData.photos.length; i++) {
      formData.append('photos[]', this.carData.photos[i]);
    }

    const headers = new HttpHeaders({
      Authorization: `Bearer ${this.authToken}`,
    });

    this.http
      .post('http://localhost:8000/api/cars', formData, { headers })
      .subscribe(
        (response) => {
          console.log('Car added successfully', response);
          this.resetForm();
          this.fetchCars();
        },
        (error) => {
          console.error('Failed to add car', error);
        }
      );
  }

  resetForm() {
    this.carData = {
      brand: '',
      model: '',
      licensePlate: '',
      dailyRent: 0,
      photos: [],
      availability: 'available',
    };
  }

  fetchCars() {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${this.authToken}`,
    });

    const params = {
      ...this.filters,
      page: this.currentPage.toString(),
    };

    this.http
      .get('http://localhost:8000/api/cars', { headers, params })
      .subscribe(
        (response: any) => {
          this.cars = response.data.map((car: any) => {
            if (typeof car.photos === 'string') {
              car.photos = JSON.parse(car.photos);
            }
            return car;
          });
          this.filteredCars = this.cars;
          this.totalPages = response.last_page;
          console.log('Cars fetched successfully', this.cars);
        },
        (error) => {
          console.error('Failed to fetch cars', error);
        }
      );
  }

  applyFilters() {
    this.currentPage = 1; // Reset to the first page when applying filters
    this.fetchCars();
  }

  previousPage() {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.fetchCars();
    }
  }

  nextPage() {
    if (this.currentPage < this.totalPages) {
      this.currentPage++;
      this.fetchCars();
    }
  }

  editCar(car: any) {
    // Redirect to edit page or open modal for editing
    this.router.navigate(['/edit-car', car.id]);
  }

  deleteCar(carId: number) {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${this.authToken}`,
    });

    this.http
      .delete(`http://localhost:8000/api/cars/${carId}`, { headers })
      .subscribe(
        (response) => {
          console.log('Car deleted successfully', response);
          this.fetchCars();
        },
        (error) => {
          console.error('Failed to delete car', error);
        }
      );
  }

  // get filteredCars() {
  //   return this.cars.filter(
  //     (car) =>
  //       car.brand.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
  //       car.model.toLowerCase().includes(this.searchQuery.toLowerCase())
  //   );
  // }
}
