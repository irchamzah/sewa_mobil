import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { RegisterComponent } from './register/register.component';
import { LoginComponent } from './login/login.component';
import { HomeComponent } from './home/home.component';
import { CarManagementComponent } from './car-management/car-management.component';
import { EditCarComponent } from './edit-car/edit-car.component';

const routes: Routes = [
  { path: 'login', component: LoginComponent }, // Tambahkan rute untuk login
  { path: 'register', component: RegisterComponent },
  { path: 'cars', component: CarManagementComponent },
  { path: 'edit-car/:id', component: EditCarComponent },
  { path: 'home', component: HomeComponent },
  { path: '', redirectTo: '/', pathMatch: 'full' },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule {}
