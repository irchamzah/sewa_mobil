import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router'; // Tambahkan ini

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css'],
})
export class RegisterComponent {
  registerData = {
    name: '',
    email: '',
    password: '',
    address: '',
    phone: '',
    driverLicense: '',
  };

  constructor(private http: HttpClient, private router: Router) {} // Tambahkan Router ke konstruktor

  onRegister() {
    this.http
      .post('http://localhost:8000/api/register', this.registerData)
      .subscribe(
        (response) => {
          console.log('Registration successful', response);
          this.router.navigate(['/login']); // Arahkan ke halaman login
        },
        (error) => {
          console.error('Registration failed', error);
        }
      );
  }
}
