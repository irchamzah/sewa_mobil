import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
})
export class LoginComponent {
  loginData = {
    email: '',
    password: '',
  };

  constructor(private http: HttpClient, private router: Router) {}

  onLogin() {
    this.http.post('http://localhost:8000/api/login', this.loginData).subscribe(
      (response: any) => {
        console.log('Login successful', response);
        // Simpan token atau data pengguna jika perlu
        localStorage.setItem('authToken', response.token);
        // Arahkan ke halaman beranda atau halaman lain setelah login
        this.router.navigate(['/home']);
      },
      (error) => {
        console.error('Login failed', error);
      }
    );
  }
}
