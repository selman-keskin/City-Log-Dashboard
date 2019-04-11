using System;
using System.Collections;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace LogProducer
{
    class Program
    {
        static void Main(string[] args)
        {

            //Int32 unixTimestamp = (Int32)(DateTime.UtcNow.Subtract(new DateTime(1970, 1, 1))).TotalSeconds;

            ArrayList array1 = new ArrayList();
            array1.Add("INFO");
            array1.Add("WARN");
            array1.Add("FATAL");
            array1.Add("DEBUG");
            array1.Add("ERROR");

            ArrayList array2 = new ArrayList();
            array2.Add("Istanbul\tHello-from-Istanbul");
            array2.Add("Tokyo\tHello-from-Tokyo");
            array2.Add("Moskow\tHello-from-Moskow");
            array2.Add("Beijing\tHello-from-Beijing");
            array2.Add("London\tHello-from-London");

            //Console.WriteLine(unixTimestamp);
            //Console.WriteLine(localDate.ToString("yyyy-MM-dd HH:mm:ss:fff")  +  "\tINFO" + "\tTokyo" + "\tHello-from-Tokyo");

            Random rnd = new Random();

            int i = 0;
            //int minutesAdd = 0;

            for (i=0;i <200;i++)
            { 
                using (StreamWriter writer = new StreamWriter(@"C:\WinNMP\WWW\logs\" + "log" + i + ".txt"))
                {
                    while (true)
                    {
                        //minutesAdd++;
                        //Console.WriteLine(minutesAdd);

                        int ri = rnd.Next(0, 5);
                        int rj = rnd.Next(0, 5);

                        DateTime localDate = DateTime.Now;
                        writer.WriteLine(localDate.ToString("yyyy-MM-dd HH:mm:ss.fff") + "\t" + array1[ri] + "\t" + array2[rj]);
                        writer.Flush();

                        long length = new System.IO.FileInfo(@"C:\WinNMP\WWW\logs\" + "log" + i + ".txt").Length;
                        Console.WriteLine(length);

                        if (length >= 2*1024*1024)
                        {
                            writer.Close();
                            break;

                        }


                    }
                } 


            }


        }
    }
}
