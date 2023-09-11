import * as React from 'react';


import { DemoContainer, DemoItem } from '@mui/x-date-pickers/internals/demo';
import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers/DatePicker';
import { DesktopDatePicker } from '@mui/x-date-pickers/DesktopDatePicker';
import { MobileDatePicker } from '@mui/x-date-pickers/MobileDatePicker';

/*
import { DemoContainer, DemoItem, AdapterDayjs, LocalizationProvider, DatePicker,DesktopDatePicker, MobileDatePicker } from '@mui/x-date-pickers';*/
import dayjs from 'dayjs';
import esLocale from 'date-fns/locale/es'; 

export default function ResponsivePickers() {

  const minDate = dayjs();

  console.log(minDate);

  console.log(esLocale)

  return (
  
    <LocalizationProvider dateAdapter={AdapterDayjs} locale={esLocale}>
        <label className={'labelEditor'}> Fecha del documento </label>   
        <DemoContainer
          components={['DatePicker', 'DesktopDatePicker', 'MobileDatePicker']}
        >

        <DemoItem label="Responsive variant">
          <DatePicker defaultValue={dayjs('2022-04-17')} />
        </DemoItem>

        <DemoItem label="Desktop variant">
          <DesktopDatePicker defaultValue={dayjs('2022-04-17')} />
        </DemoItem>

        <DemoItem label="Mobile variant">
          <MobileDatePicker defaultValue={dayjs('2023-09-11')}  views={['year', 'month', 'day']} minDate={minDate} locale={esLocale}/>
        </DemoItem>    

      </DemoContainer>
    </LocalizationProvider>
  );
}

