using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.IO;

using KafkaNet;
using KafkaNet.Model;
using KafkaNet.Protocol;

namespace KafkaProducer
{
    internal class Program
    {
        private static void Main(string[] args)
        {
            var kafkaOptions = new KafkaOptions(new Uri("http://localhost:9092"));
            var brokerRouter = new BrokerRouter(kafkaOptions);
            var producer = new Producer(brokerRouter);

            DirectoryInfo d = new DirectoryInfo(@"C:\WinNMP\WWW\logs");//Assuming Test is your Folder
            FileInfo[] Files = d.GetFiles("log*.txt"); //Getting Log files
            string str = "";
            foreach (FileInfo file in Files)
            {
                //str = str + ", " + file.Name;
                Console.WriteLine(file.Name);
           

            using (var reader = new StreamReader(@"C:\WinNMP\WWW\logs\" + file.Name))
            {
                string line;
                while ((line = reader.ReadLine()) != null)
                {
                    // Do stuff with your line here, it will be called for each 
                    // line of text in your file.
                    Console.WriteLine(line);

                    producer.SendMessageAsync("Logs",
                            new[] {
                                            //new Message("4239874 log1"),
                                            //new Message("3246872 log2"),
                                            new Message(line)
                            }); //.Wait();


                }
            }

            }
        }
    }
}
